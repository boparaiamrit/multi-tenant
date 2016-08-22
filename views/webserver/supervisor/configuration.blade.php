;#
;#   Auto generated Fpm configuration
;#       @time: {{ date('H:i:s d-m-Y') }}
;#       @author: boparaiamrit@gmail.com
;#

;# unique fpm group
[program:{{ $Host->identifier }}]
process_name=%(program_name)s_%(process_num)02d
command=/usr/bin/php {{ $base_path }}/artisan queue:work beanstalkd --sleep=3 --tries=3 --daemon --customer={{$Host->identifier}}
autostart=true
autorestart=true
user={{ $user }}
numprocs=2
redirect_stderr=true
stdout_logfile={{ $base_path }}/storage/logs/supervisor-{{ $Host->identifier }}.log