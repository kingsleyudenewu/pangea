<h1 align="center">Pangea Home Test</h1>
<h4>This project is an implementation of an HTTP-based notification system.
When a message is published on a topic, it should be forwarded to all subscriber endpoints.</h4>

## Pangea Application Setup
- **Run this command pn your root project chmod +x ./start-server.sh**
- **sudo ./init-server.sh**

## **Steps**

- On the **Subscriber**, run ```php artisan serve --port=8000``` to start it.
- On the **Publish**, run ```php artisan serve --port=9000``` to start it.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
