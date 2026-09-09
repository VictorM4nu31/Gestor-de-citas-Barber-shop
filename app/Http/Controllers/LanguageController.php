<?php

namespace App\Http\Controllers;

use App\Http\Requests\LanguageSwitchRequest;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Switch the application language with validation and security
     *
     * @return RedirectResponse|JsonResponse
     */
    public function switch(LanguageSwitchRequest $request, string $locale)
    {
        try {
            // The locale is already validated by the LanguageSwitchRequest
            $validatedLocale = $request->getValidatedLocale();

            // Additional security check - ensure the route parameter matches the validated input
            if ($locale !== $validatedLocale) {
                Log::warning('Language switch security violation', [
                    'route_locale' => $locale,
                    'validated_locale' => $validatedLocale,
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                return $this->handleInvalidRequest($request);
            }

            // Store previous locale for potential rollback
            Session::put('previous_locale', app()->getLocale());

            // Store the locale in session
            Session::put('locale', $validatedLocale);

            // Force session save to ensure persistence
            Session::save();

            // Log successful language switch for monitoring
            Log::info('Language switched successfully', [
                'locale' => $validatedLocale,
                'ip' => $request->ip(),
                'previous_locale' => Session::get('previous_locale', 'unknown'),
            ]);

            // Handle AJAX requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'locale' => $validatedLocale,
                    'message' => __('common.language_switched_successfully'),
                ]);
            }

            // Get the previous URL or redirect to home
            $previousUrl = $request->header('referer') ?? route('welcome');

            // Remove any existing lang parameter from the URL
            $redirectUrl = $this->removeLanguageParameter($previousUrl);

            return Redirect::to($redirectUrl)
                ->with('success', __('common.language_switched_successfully'));

        } catch (ThrottleRequestsException $e) {
            Log::warning('Language switch rate limit exceeded', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'attempted_locale' => $locale,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => $e->getMessage(),
                    'retry_after' => $e->getHeaders()['Retry-After'] ?? 60,
                ], 429);
            }

            return redirect()->back()
                ->withErrors(['locale' => $e->getMessage()]);

        } catch (\Exception $e) {
            Log::error('Language switch error', [
                'error' => $e->getMessage(),
                'locale' => $locale,
                'ip' => $request->ip(),
            ]);

            return $this->handleInvalidRequest($request);
        }
    }

    /**
     * Handle invalid language switch requests
     *
     * @return RedirectResponse|JsonResponse
     */
    private function handleInvalidRequest(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'error' => __('common.errors.invalid_locale'),
                'locale' => config('app.fallback_locale', 'es'),
            ], 400);
        }

        return redirect()->back()
            ->withErrors(['locale' => __('common.errors.invalid_locale')])
            ->withInput();
    }

    /**
     * Remove language parameter from URL to prevent conflicts
     */
    private function removeLanguageParameter(string $url): string
    {
        // Validate URL to prevent open redirect attacks
        if (! $this->isValidRedirectUrl($url)) {
            return route('welcome');
        }

        $parsedUrl = parse_url($url);

        if (! isset($parsedUrl['query'])) {
            return $url;
        }

        parse_str($parsedUrl['query'], $queryParams);
        unset($queryParams['lang']);

        $newQuery = http_build_query($queryParams);
        $newUrl = $parsedUrl['scheme'].'://'.$parsedUrl['host'];

        if (isset($parsedUrl['port'])) {
            $newUrl .= ':'.$parsedUrl['port'];
        }

        if (isset($parsedUrl['path'])) {
            $newUrl .= $parsedUrl['path'];
        }

        if (! empty($newQuery)) {
            $newUrl .= '?'.$newQuery;
        }

        if (isset($parsedUrl['fragment'])) {
            $newUrl .= '#'.$parsedUrl['fragment'];
        }

        return $newUrl;
    }

    /**
     * Validate redirect URL to prevent open redirect attacks
     */
    private function isValidRedirectUrl(string $url): bool
    {
        $parsedUrl = parse_url($url);
        $appUrl = parse_url(config('app.url'));

        // Ensure the URL belongs to the same domain
        if (isset($parsedUrl['host']) && $parsedUrl['host'] !== $appUrl['host']) {
            return false;
        }

        // Ensure it's not a javascript: or data: URL
        if (isset($parsedUrl['scheme']) &&
            ! in_array($parsedUrl['scheme'], ['http', 'https'])) {
            return false;
        }

        return true;
    }
}
