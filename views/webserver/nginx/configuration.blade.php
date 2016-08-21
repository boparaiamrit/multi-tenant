#
#   Auto generated Nginx configuration
#       @time: {{ date('H:i:s d-m-Y') }}
#       @author: boparaiamrit-me/webserver
#       @website: "{{ $website->present()->name }}"
#

#
#   Hostnames with certificate
#
@foreach($website->hostnamesWithCertificate as $hostname)
    @include('webserver::nginx.includes.server-block', [
        'hostname' => $hostname,
        'ssl' => $hostname->certificate
    ])
@endforeach

#
#   Hostnames without certificate
#
@if($website->hostnamesWithoutCertificate->count() > 0)
    @include('webserver::nginx.includes.server-block', [
        'hostnames' => $website->hostnamesWithoutCertificate
    ])
@endif
