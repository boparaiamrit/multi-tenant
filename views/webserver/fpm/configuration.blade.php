;#
;#   Auto generated Fpm configuration
;#       @time: {{ date('H:i:s d-m-Y') }}
;#       @author: boparaiamrit@gmail.com
;#

;# unique fpm group
[{{ $Host->identifier }}]

;# listening for nginx proxying
listen=/run/php/php7.0-fpm.{{ $Host->identifier }}.sock
listen.allowed_clients=127.0.0.1


;# user under which the application runs
user = {{ $user }}

;# group under which the application runs
group = {{ config('webserver.group') }}

listen.owner = {{ $user }}
listen.group = {{ config('webserver.group') }}
listen.mode  = 0666

;# fpm pool management variables
pm=dynamic
pm.max_children         = 20
pm.start_servers        = 5
pm.min_spare_servers    = 5
pm.max_spare_servers    = 10
pm.max_requests         = 20

;# force fpm workers into the following path
chdir = {{ $base_path }}