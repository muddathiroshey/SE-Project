use freelance_marketplace;
create table userData(
    id int primary key auto_increment,
    user_email varchar(255) unique not null,
    user_password varchar(255) not null,
    user_name varchar(255) not null,
    user_role enum('Freelancer', 'Client', 'Admin','Arbitrator') default 'Client' 
);