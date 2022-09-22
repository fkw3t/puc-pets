# CONSULTA PETS TODO



<h2>project structure</h2>

* [] Modelagem do banco
* [] Modelagem de entidades
    * [] Relacionamentos
* [] Estruturação de endpoints
    * [X] Auth
        * [X] cadastro 
        * [X] login 
        * [X] logout
        * [X] token stats
    * [X] Usuario
        * [X] visualizar usuario por cnpj
        * [X] visualizar usuario por documento
        * [X] editar usuario
        * [X] remover usuario
    * [] Pet
        * [] cadastrar pet
        * [] visualizar pet
        * [] editar pet
        * [] remover pet
    * [] Veterinario
        * [] cadastrar veterinario
        * [] visualizar veterinario
        * [] editar veterinario
        * [] remover veterinario
    * [] Agenda
        * [] cadastrar agenda
        * [] buscar agendas
        * [] buscar agenda de um veterinario especifico
        * [] editar agenda
        * [] remover agenda
    * []  
* [] API Resources
* [] Authorization
    * [] Usuario
    * [] Despesas
* [] Notification
* [] Testes
* [] Documentação - Swagger

<h2>modelagem banco de dados</h2>

- table: user
    * id: int
    * name: varchar
    * document_id: varchar
    * email: varchar
    * phone: varchar
    * password: varchar
    * role: enum
    * timestamps: datetime

- table: pet
    * id: int
    * name: varchar
    * size: enum
    * timestamps: datetime

- table: vet
    * id: int
    * user_id: int
    * crm: int
    * specialization: varchar

- table: schedule
    * id: int
    * vet_id: int
    * user_id: int
    * date: datetime
    * status: enum

- table: services
    * id: int
    * description: varchar

- table: consult
    * id: int
    * client_id: int
    * schedule_id: int
    * price: decimal

- table: consults_services
    * consult_id: int
    * service_int: int

- table: consults_pets
    * consult_id: int
    * pet_id: int
