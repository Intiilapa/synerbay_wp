create table sb_rfq
(
    id bigint unsigned auto_increment
        primary key,
    user_id bigint unsigned not null,
    product_id bigint unsigned not null,
    qty int(32) not null,
    created_at datetime default CURRENT_TIMESTAMP null,
    constraint sb_rfq_sb_posts_ID_fk
        foreign key (product_id) references sb_posts (ID),
    constraint sb_rfq_sb_users_ID_fk
        foreign key (user_id) references sb_users (ID)
);

