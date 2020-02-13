define(['jquery', 'core/ajax', 'local_eabccoursegrades/vue', 'local_eabccoursegrades/jspdf'], function ($, ajax, Vue, jsPDF) {
//define(['jquery', 'core/ajax', 'local_eabccoursegrades/vue'], function ($, ajax, Vue) {
    "use strict";

    var get_grades = function (courseid, params) {
        return new Promise(function (resolve, reject) {
            var promises = ajax.call([
                {
                    methodname: 'local_eabccoursegrades_get_grades',
                    args: {
                    }
                }
            ]);
            promises[0].done(function (result) {
                var users = result[0].users;
                resolve(users);
            }).fail(function (ex) {
                console.error(ex);
                reject(ex);
            });
        })
    };

    function init() {
        new Vue({
            data: {
                users: []
            },
            mounted: function () {
                var self = this;
                get_grades().then(function (result) {
                    self.users = result;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            methods: {
                grades: function () {
                    var self = this;
                    get_grades().then(function (result) {
                        self.users = result;
                    }).catch(function (error) {
                        console.log(error);
                    });
                },
                generate_pdf: function () {
                    var doc = new jsPDF();

                    doc.text(20, 20, 'Hola mundo');
                    doc.text(20, 30, 'Vamos a generar un pdf desde el lado del cliente');

                    // Add new page
                    doc.addPage();
                    doc.text(20, 20, 'Visita programacion.net');

                    // Save the PDF
                    doc.save('documento.pdf');
                }
            },
            template: `
            <div>
            <button v-on:click="generate_pdf" class="btn btn-primary">Generar PDF</button>
                <ul class="list-group">
                    <li class="list-group-item col-md-3">
                        <select class="form-control">
                            <option value="">Plan</option>
                            <option value="">Sede</option>
                            <option value="">Materia</option>
                            <option value="">Curso</option>
                            <option value="">Docente</option>
                        </select> 
                    </li>
                    <li class="list-group-item col-md-3">
                        <input type="text" placeholder="Buscar" class="form-control" v-model="name">
                    </li>
                </ul>
                <div class="table-responsive">
                
                    <table class="table" id="tablegrades">
                        <thead>
                            <tr>
                                <th scope="col">Username</th>
                                <th scope="col">Nombre</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(user, index) in users">
                                <td>{{user.username}}</td>
                                <td>{{user.name}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>`
        }).$mount('#gradesreport');
    };

    return {
        init: init
    };
});