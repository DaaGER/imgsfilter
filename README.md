# imgsfilter

Проксирование запросов на картинки через Nginx и их последующее извлечение из кэша или передача бэкенду для обработки и кэширование результата.


tmpfs /var/cache/nginx tmpfs noatime,nodiratime,nodev,nosuid,uid=33,gid=33,mode=0700,size=512M 0 0
mount /var/cache/nginx
