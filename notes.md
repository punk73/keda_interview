

so basically we need to make two models :
- Customer
- Staff

todo :
- install passport for JWT

- make login:
    - send email & password
    - give back token if success

message content :
- id *
- sender_id **
- recipient_id **
- content
- created_at
- updated_at
- is_report = ??

ENDPOINT :
-  /messages post
- /messages get


customer = user with specific user_type
staff = user with specific user_type

notes :
- we need to implement authorizations on messages features.
