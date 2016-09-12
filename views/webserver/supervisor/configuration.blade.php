;#
;#   Auto generated Fpm configuration
;#       @time: {{ date('H:i:s d-m-Y') }}
;#       @author: boparaiamrit@gmail.com
;#

;# unique fpm group
[program:{{ $Host->identifier }}]
command=/usr/bin/php {{ $base_path }}/artisan queue:work beanstalkd --sleep=3 --tries=3 --daemon --hostname={{$Host->identifier}} --queue="{{$Host->identifier}}"
autostart=true
autorestart=true
user={{ $user }}
redirect_stderr=true
stdout_logfile={{ $base_path }}/storage/logs/supervisor-{{ $Host->identifier }}.log

[program:{{ $Host->identifier }}.gravatar]
command=/usr/bin/php {{ $base_path }}/artisan queue:work beanstalkd --sleep=3 --tries=3 --daemon --hostname={{$Host->identifier}} --queue="{{$Host->identifier}}.gravatar"
autostart=true
autorestart=true
user={{ $user }}
redirect_stderr=true
stdout_logfile={{ $base_path }}/storage/logs/supervisor-{{ $Host->identifier }}.log

[program:{{ $Host->identifier }}.twitter]
command=/usr/bin/php {{ $base_path }}/artisan queue:work beanstalkd --sleep=3 --tries=3 --daemon --hostname={{$Host->identifier}} --queue="{{$Host->identifier}}.twitter"
autostart=true
autorestart=true
user={{ $user }}
redirect_stderr=true
stdout_logfile={{ $base_path }}/storage/logs/supervisor-{{ $Host->identifier }}.log