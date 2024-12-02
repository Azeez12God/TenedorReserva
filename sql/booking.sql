drop table if exists booking cascade;

create table booking(

    bookinguuid VARCHAR(36),
    useruuid VARCHAR(36),
    bookingdate DATE NOT NULL,
    bookingunits INT NOT NULL ,
    bookingcost FLOAT NOT NULL,
    clientcode VARCHAR(36),
    bookingpaymethod ENUM('PAYPAL', 'APPLE_PAY', 'GOOGLE_PAY', 'CARD', 'BIZUM') NOT NULL ,
    bookingchanges INT

);

alter table booking add constraint pk_booking PRIMARY KEY (bookinguuid);
alter table booking add constraint fk_booking_user
    FOREIGN KEY (useruuid) references user(useruuid) on delete cascade;
/*
alter table booking add constraint fk_booking_client
    FOREIGN KEY (clientcode) references client(clientcode) on delete cascade;*/