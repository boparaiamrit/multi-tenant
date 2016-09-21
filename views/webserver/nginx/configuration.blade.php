server {
    listen {!! $port !!};
    server_name {!! $host_name !!};

    add_header Access-Control-Allow-Origin *;
    add_header Access-Control-Request-Method GET;

    if ( $host ~* ^www\.(.*) ) {
        set             $host_nowww     $1;
        rewrite         ^(.*)$          $scheme://$host_nowww$1 permanent;
    }

    root {!! public_path() !!};
    index index.php;

    error_log {!! $log_path !!}.error.log notice;

    location / {
        index index.php;
        try_files $uri $uri/ $uri/index.php?$args /index.php?$args;
    }

    location ~ \.php(/|$) {
        fastcgi_pass {!! $listen_socket !!};
        include fastcgi_params;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_read_timeout 300;
    }
}