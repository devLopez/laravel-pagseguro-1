<script type="text/javascript">

function confirmBoleto() {
    $("#confirmBoleto").attr("disabled", "disabled");
    document.getElementById("loadPagamento").style.display = "block";
    senderHash = PagSeguroDirectPayment.getSenderHash();
    $.post("{{ route('PagSeguroAjaxSenderHash') }}", {
        _token: "{{ csrf_token() }}",
        data: (senderHash)
    });
    setTimeout(function() {
        window.location.href = "{{ route('enviaPagamento', 'boleto') }}";
    }, 2500);
}

function setSenderHash() {
    senderHash = PagSeguroDirectPayment.getSenderHash();
    setTimeout(function() {
        $.post("{{ route('PagSeguroAjaxSenderHash') }}", {
            _token: "{{ csrf_token() }}",
            data: (senderHash)
        });
    }, 1000);
}

function setInfoHolder() {
    $.post("{{ route('PagSeguroAjaxInfoHolder') }}", {
        _token: "{{ csrf_token() }}",
        holderName: $("#holderName").val(),
        holderCpf: $("#holderCpf").val(),
        holderBirthDate: $("#holderBirthDate").val()
    });
}

window.onload = function() {

    $("#cardNumber").blur(function() {
        var cardNumber = document.getElementById("cardNumber").value;
        PagSeguroDirectPayment.getBrand({
            cardBin: cardNumber.replace(/ /g, ''),
            success: function(data) {
                var brand = JSON.stringify(data.brand.name).replace(/"/g, '');
                $("#brand").fadeIn(600);
                $("#brandName").html(brand);
            }
        });
    });


    $('#formCartao').formValidation({
        framework: 'bootstrap',
        icon: {
            valid: '',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            cardNumber: {
                trigger: 'blur',
                validators: {
                    notEmpty: {
                        message: 'Campo obrigatório.'
                    }
                }
            },
            expirationMonth: {
                trigger: 'blur',
                validators: {
                    notEmpty: {
                        message: 'Campo obrigatório.'
                    }
                }
            },
            expirationYear: {
                trigger: 'blur',
                validators: {
                    notEmpty: {
                        message: 'Campo obrigatório.'
                    }
                }
            },
            cvv: {
                trigger: 'blur',
                validators: {
                    notEmpty: {
                        message: 'Campo obrigatório.'
                    }
                }
            },
            holderName: {
                trigger: 'blur',
                validators: {
                    notEmpty: {
                        message: 'Campo obrigatório.'
                    }
                }
            },
            holderBirthDate: {
                trigger: 'blur',
                validators: {
                    notEmpty: {
                        message: 'Campo obrigatório.'
                    },
                    date: {
                        format: 'DD/MM/YYYY',
                        message: 'Preenchimento incompleto.'
                    }
                }
            },
            holderCpf: {
                trigger: 'blur',
                validators: {
                    notEmpty: {
                        message: 'Campo obrigatório.'
                    },
                    id: {
                        country: 'BR',
                        message: 'Por favor, digite um CPF válido.'
                    }
                }
            },

        }
    }).on('success.form.fv', function(e) {
        e.preventDefault();

        var $form = $(e.target),
            fv = $(e.target).data('formValidation');

        var parametros = {

            cardNumber: $("#cardNumber").val(),
            cvv: $("#cvv").val(),
            expirationMonth: $("#expirationMonth :selected").val(),
            expirationYear: $("#expirationYear :selected").val(),
            success: function(data) {

                $.post("{{ route('PagSeguroAjaxCreditCardToken') }}", {
                    _token: "{{ csrf_token() }}",
                    data: (JSON.stringify(data.card.token).replace(/"/g, ''))
                });
            }
        }

        $("#confirmCartao").attr("disabled", "disabled");
        document.getElementById("loadPagamento").style.display = "block";

        setSenderHash();
        setInfoHolder();
        PagSeguroDirectPayment.createCardToken(parametros);

        setTimeout(function() {
            window.location.href = "{{ route('enviaPagamento', 'credit_card') }}";
        }, 2500);
    });
}
</script>