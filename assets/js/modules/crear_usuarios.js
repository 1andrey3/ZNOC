$(function () {
    usuario = {
        init: function () {
            usuario.events();
            usuario.loadAreasToCharge();
        },

        events: function () {
            $('#newUser').click(usuario.validateData);
            $('#area').change(usuario.loadRolesByArea);
        },

        validateData: function () {
            $(".err").removeClass("err");
            const campos = $("div.frame input,div.frame select, div.frame textarea");
            var vacios = [];
            var data = {};
            $.each(campos, function (i, element) {
                if ($(element).prop("type") != "checkbox") {
                    if ($(element).val() == null || $(element).val() == '' || $(element).val() == ' ' || $(element).val() == '  ') {
                        vacios.push($(element).attr("id"));
                    } else {
                        data[$(element).attr("id")] = $(element).val();
                    }
                }

            });
//            console.log(data);
            if (vacios.length != 0) {
                $.each(vacios, function (i, id) {
                    $(`#${id}`).addClass('err');
                });
                swal({
                    "html": "¡No puede dejar los campo en rojo vacios!",
                    "type": "error"
                });
//                console.log("vacios", vacios);

            } else {
                usuario.saveNewUser(data);
            }

        },

        saveNewUser: function (bitacora) {
            $.post(base_url + "User/c_saveUser", {data: JSON.stringify(bitacora)},
                    function (bool) {
                        let msj = '';
                        if (bool) {
                            msj = ['Usuario creado corectamente.', 'success'];
                        } else {
                            msj = ['Hubo un error inesperado.', 'error'];
                        }

                        swal({
                            "html": msj[0],
                            "type": msj[1]
                        }).then(() => {
                            location.reload();
                        });

                    },
                    );
        },

        loadAreasToCharge: function () {
            helper.showLoading();

            $.post(base_url + "User/c_getAreasToCharge", {
                rol: 'ingeniero',
                area: 'Dilo_BackOffice',
            },
                    function (data) {
                        const obj = JSON.parse(data);

                        $.each(obj, function (i, val) {
                            $('#area').append('<option value="' + val.area + '">' + val.area + '</option>');
                        });

                        helper.hideLoading();
                    }
            );

        },

        loadRolesByArea: function () {
            helper.showLoading();

            if ($('#area').val() != '') {
                $.post(base_url + "User/c_getRolesByArea", {
                    area: $('#area').val(),
                },
                        function (data) {
                            const obj = JSON.parse(data);

                            $.each(obj, function (i, val) {
                                $('#role').append('<option value="' + val.id + '">' + val.name + '</option>');
                            });

//                            helper.hideLoading();
                        }
                );
            } else {
                $('#role option').each(function () {
                    if ($(this).val() != '') {
                        $(this).remove();
                    }
                });
            }

            helper.hideLoading();
        },

    }
    usuario.init();
});