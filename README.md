## Spuštění sandboxu

- Je důležíté mít nainstalovaný docker, docker-compose a make
- V `.env` souboru je důležité nastavit cestu do tohoto projektu, pro správnou inicializaci docker kontejnerů
- Pro prvotní start aplikace použijte příkaz `make docker-up` a následně musíte pustit tento příkaz načtení migrací a stažení nutných balíčků
  - `docker exec -it positive_php make build`
- Zde se nachází detailní api specifikace [odkaz](https://www.postman.com/orbital-module-architect-68564260/workspace/bepositive-test/collection/15159008-459c9daa-c903-4419-b45c-35b5dbc644e9?action=share&creator=15159008)
- Níže se nachází Api dokumentace

# bePositive API Specification

## Customer Endpoints

### Get all customers

- **Method:** GET
- **URL:** [http://0.0.0.0:821/api/v1/customer/](http://0.0.0.0:821/api/v1/customer/)
- **Description:** Retrieves a list of all customers.

### Get specific customer

- **Method:** GET
- **URL:** [http://0.0.0.0:821/api/v1/customer/2](http://0.0.0.0:821/api/v1/customer/2)
- **Description:** Retrieves details of a specific customer identified by ID.

### Create new customer

- **Method:** PUT
- **URL:** [http://127.0.0.1:821/api/v1/customer/create](http://127.0.0.1:821/api/v1/customer/create)
- **Description:** Creates a new customer with provided information in the request body.

### Edit customer

- **Method:** POST
- **URL:** [http://0.0.0.0:821/api/v1/customer/1/edit](http://0.0.0.0:821/api/v1/customer/1/edit)
- **Description:** Modifies details of an existing customer identified by ID using the information provided in the request body.

### Remove customer

- **Method:** DELETE
- **URL:** [http://0.0.0.0:821/api/v1/customer/2/delete](http://0.0.0.0:821/api/v1/customer/2/delete)
- **Description:** Deletes a customer identified by ID.

## Product Endpoints

### Get all products

- **Method:** GET
- **URL:** [http://0.0.0.0:821/api/v1/product/](http://0.0.0.0:821/api/v1/product/)
- **Description:** Retrieves a list of all products.

### Get specific product

- **Method:** GET
- **URL:** [http://0.0.0.0:821/api/v1/product/1](http://0.0.0.0:821/api/v1/product/1)
- **Description:** Retrieves details of a specific product identified by ID.

### Create new product

- **Method:** PUT
- **URL:** [http://0.0.0.0:821/api/v1/product/create](http://0.0.0.0:821/api/v1/product/create)
- **Description:** Creates a new product with provided information in the request body.

### Remove product

- **Method:** DELETE
- **URL:** [http://0.0.0.0:821/api/v1/product/1/delete](http://0.0.0.0:821/api/v1/product/1/delete)
- **Description:** Deletes a product identified by ID.

## Order Endpoints

### Get all orders

- **Method:** GET
- **URL:** [http://0.0.0.0:821/api/v1/order/](http://0.0.0.0:821/api/v1/order/)
- **Description:** Retrieves a list of all orders.

### Get specific order

- **Method:** GET
- **URL:** [http://0.0.0.0:821/api/v1/order/2](http://0.0.0.0:821/api/v1/order/2)
- **Description:** Retrieves details of a specific order identified by ID.

### Get order by customer

- **Method:** GET
- **URL:** [http://0.0.0.0:8000/api/v1/order/3/customer](http://0.0.0.0:8000/api/v1/order/3/customer)
- **Description:** Retrieves orders associated with a specific customer identified by ID.

### New order

- **Method:** PUT
- **URL:** [http://0.0.0.0:821/api/v1/order/create](http://0.0.0.0:821/api/v1/order/create)
- **Description:** Creates a new order with provided information in the request body.

### Edit order status

- **Method:** POST
- **URL:** [http://0.0.0.0:8000/api/v1/order/6/edit](http://0.0.0.0:8000/api/v1/order/6/edit)
- **Description:** Modifies the status of an existing order identified by ID using the information provided in the request body.
