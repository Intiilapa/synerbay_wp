create table sb_offers
(
    id bigint unsigned auto_increment,
    product_id bigint unsigned not null,
    user_id bigint unsigned not null,
    delivery_date datetime default current_timestamp not null,
    offer_start_date datetime default current_timestamp not null,
    offer_end_date datetime default current_timestamp not null,
    price_steps varchar(512) not null,
    minimum_order_quantity int(32) unsigned not null comment 'pl.: a rendelhető mennyiség 50db-ról indul',
    order_quantity_step int(32) unsigned not null comment 'ennyivel növelhető a megrendelt mennyiség',
    max_total_offer_qty int(32) unsigned null comment 'ezzel szabod meg az ajánlatban szereplő termék felső mennyiségi határát, üresen hagyható, akkor nincs limit',
    transport_parity enum('exw', 'fca', 'cpt', 'cip', 'dpu', 'dap', 'ddp', 'fas', 'fob', 'cfr', 'cif', 'daf', 'dat', 'des', 'deq', 'ddu') not null,
    shipping_to varchar(255) not null,
    created_at datetime default CURRENT_TIMESTAMP null,
    constraint sb_offers_pk
        primary key (id),
    constraint sb_offers_sb_posts_ID_fk
        foreign key (product_id) references sb_posts (ID),
    constraint sb_offers_sb_users_ID_fk
        foreign key (user_id) references sb_users (ID)
);

create index offer_delivery_date_index
    on sb_offers (delivery_date desc);

create index offer_max_total_offer_qty_index
    on sb_offers (max_total_offer_qty);

create index offer_minimum_order_quantity_index
    on sb_offers (minimum_order_quantity);

create index offer_end_date_index
    on sb_offers (offer_end_date);

create index offer_start_date_index
    on sb_offers (offer_start_date);

create index offer_shipping_to_index
    on sb_offers (shipping_to);

create index offer_transport_parity_index
    on sb_offers (transport_parity);

