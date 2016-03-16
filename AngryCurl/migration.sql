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