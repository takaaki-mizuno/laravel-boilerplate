<?php namespace App\Helpers\Production;

use App\Helpers\RedirectHelperInterface;
use Illuminate\Routing\UrlGenerator;

class RedirectHelper implements RedirectHelperInterface
{

    /**
     * The URL generator instance.
     *
     * @var \Illuminate\Routing\UrlGenerator
     */
    protected $generator;

    /**
     * Create a new Redirector instance.
     *
     * @param \Illuminate\Routing\UrlGenerator $generator
     */
    public function __construct(UrlGenerator $generator)
    {
        $this->generator = $generator;
    }

    public function guest($path, $guardName = '', $status = 302, $headers = [], $secure = null)
    {
        \Session::put($this->getSessionKey($guardName), $this->generator->full());

        /** @var \Illuminate\Routing\Redirector $redirector */
        $redirector = app('redirect');

        return $redirector->to($path, $status, $headers, $secure);
    }

    public function intended($default = '/', $guardName = '', $status = 302, $headers = [], $secure = null)
    {
        $path = \Session::pull($this->getSessionKey($guardName), $default);

        /** @var \Illuminate\Routing\Redirector $redirector */
        $redirector = app('redirect');

        return $redirector->to($path, $status, $headers, $secure);
    }

    private function getSessionKey($guardName)
    {
        if (empty($guardName)) {
            return 'url.intended';
        }

        return 'url.intended.'.strtolower($guardName);
    }

}
