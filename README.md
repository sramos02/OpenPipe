### Welcome to OpenPipe

OpenPipe is an HTTP pipeline presented as a thin layer of application logic on top of HTTP which attempts to optimize the request cycle in a manner that allow pieces of a full web page to load and display independently.  This added layer is developed as a software library containing both server-side and client-side application code.

The resulting framework processes, requests, and delivers responses in an optimized manner taking into account current HTTP limitations that are inherent to the overhead created by the connect and request style of communication. The result of this response optimization through a pipeline is an increase in perceived speed that is accomplished by displaying fully functional content as quickly as possible – even before the entire document is completely processed by the web server.

HTTP pipelining is inspired from traditional pipelining technologies utilized by today’s modern CPU’s, where an instruction’s life cycle is broken into multiple stages. Instead of instructions, HTTP pipelining breaks the page generation process into several stages.


### Authors and Contributors
In 2012, Sean Kenny (@polycoder), built the OpenPipe HTTP Pipeline Framework v1.0

### Support or Contact
Having trouble with OpenPipe? contact skenny214@gmail.com with questions
