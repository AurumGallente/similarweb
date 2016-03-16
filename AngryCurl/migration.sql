CREATE TABLE data_items
(
  id serial NOT NULL,
  data jsonb DEFAULT null,
  url text,
  category text DEFAULT null,
  timestamp timestamp default current_timestamp,
  is_parsed boolean default false,
  CONSTRAINT json_pr_pkey PRIMARY KEY (id)
);
WITH items as (
SELECT items FROM (VALUES ('facebook.com'),('google.com'),('youtube.com'),('vk.com'),('yahoo.com'), ('live.com'), ('ok.ru'), ('instagram.com'), ('wikipedia.org'), ('yandex.ru'), ('twitter.com'), ('mail.ru'), ('amazon.com'), ('google.co.uk'), ('google.com.br'), ('baidu.com'), ('xvideos.com'), ('reddit.com'), ('netfix.com'), ('tumblr.com'), ('linkedin.com'), ('ebay.com'), ('pornhub.com'), ('bing.com'), ('pinterest.com'), ('xhamster.com'), ('aliexoress.com'), ('qq.com'), ('wordpress.com'), ('microsoft.com'), ('taobao.com'), ('t.co'))
s(items)
),
current_month as (
select date_trunc('month', now()) as time
)
INSERT INTO data_items (url)
SELECT items FROM items WHERE NOT EXISTS 
(SELECT url FROM data_items, current_month WHERE data_items.url=items.items AND data_items.is_parsed=false AND data_items.is_parsed=false and date_trunc('month', data_items.timestamp)=current_month.time)
