#
#   Auto generated Nginx configuration
#       @time: {{ date('H:i:s d-m-Y') }}
#       @author: boparaiamrit@gmail.com
#

#
#   Hostnames with certificate
#
@foreach($Host->withCertificate as $Host)
    @include('webserver::nginx.includes.server-block', [
        'Host' => $Host,
        'Ssl' => $Host->certificate
    ])
@endforeach

#
#   Hostnames without certificate
#
@if($Host->withoutCertificate->count() > 0)
    @include('webserver::nginx.includes.server-block', [
        'Hosts' => $Host->withoutCertificate
    ])
@endif
