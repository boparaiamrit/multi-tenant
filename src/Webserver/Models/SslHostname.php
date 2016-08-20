<?php

namespace Hyn\Webserver\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

/**
 * @property mixed ssl_certificate_id
 * @property mixed hostname
 */
class SslHostname extends Model
{
    use PresentableTrait;

    protected $presenter = 'Hyn\Webserver\Presenters\SslHostnamePresenter';

    /**
     * @return SslCertificate
     */
    public function certificate()
    {
        return $this->belongsTo(SslCertificate::class);
    }
}
