create table sb_offer_applies
(
    id bigint unsigned auto_increment
        primary key,
    user_id bigint unsigned not null,
    offer_id bigint unsigned not null,
    qty int(32) not null,
    created_at datetime default CURRENT_TIMESTAMP null,
    status enum('pending', 'active', 'denied') default 'pending' not null,
    constraint sb_offer_applies_sb_offer_ID_fk
        foreign key (offer_id) references sb_offers (id),
    constraint sb_offer_applies_sb_users_ID_fk
        foreign key (user_id) references sb_users (ID)
);

