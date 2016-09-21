[{!! $host_identifier !!}]
listen={!! $listen_socket !!}
listen.allowed_clients=127.0.0.1

@if($machine == 'linux')
    user={!! $user !!}
    group={!! $group !!}
    listen.owner={!! $user !!}
    listen.group={!! $group !!}
    listen.mode =0666

@endif
pm=dynamic
pm.max_children=20
pm.start_servers=5
pm.min_spare_servers=5
pm.max_spare_servers=10
pm.max_requests=20

chdir={!! $base_path !!}

request_terminate_timeout=300