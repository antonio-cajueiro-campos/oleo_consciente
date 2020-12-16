var allright = "2px solid #00ff00" //neon-green
var allwrong = "2px solid #dc143c" //crimson
var allclear = "2px solid #cccccc" //gray
var verifyList = true;
var verifySpace = (string) => /\s/g.test(string);
var mediaValue = window.matchMedia("(max-width: 578px)");
mediaQueries(mediaValue);
mediaValue.addListener(mediaQueries);

function cpfCnpjCheck(id) {
    let elem = document.getElementById(id)
    let cpfCnpj = elem.value;
    if (validaCpfCnpj(cpfCnpj)) {
        elem.style.border = allright;
        elem.setAttribute("placeholder", '');
        return true;
    } else {
        elem.style.border = allwrong;
        if (id == "cnpj" || id == "cnpj2")
            elem.setAttribute("placeholder", 'Digite um CNPJ válido');
        else if (id == "cpf")
            elem.setAttribute("placeholder", 'Digite um CPF válido');
        return false;
    }
}

function mediaQueries(mediaValue) {
    if (mediaValue.matches) {
        toggleLogo = async() => {
            var logo = document.getElementById('logo');
            logo.style.display="none";
            await new Promise(r => setTimeout(r, 500));
            var sidebar = document.getElementById('sidebar');
            if (sidebar.className == "active") {
                logo.style.display="none";
            } else {
                logo.style.display="unset";
            }
        }
    } else {
        toggleLogo = () => {
            var logo = document.getElementById('logo');
            if (logo.style.display="none") {
                logo.style.display="unset";
            }
        }
    }
}
  
function emailCheck(id) {
    let elem = document.getElementById(id);
    let endereco = elem.value
    // validação de email restritiva
    // let validacaoRestritiva  = /^[\w_\.-]+@(hotmail|yahoo|gmail|outlook|terra|uol)\.(com$|com\.br$)/;

    // validação de email abrangente
    let validacaoAbrangente  = /^[\w_\.-]+@[\w_\.-]+\.[\w_\.-]+$/;
  
    if (validacaoAbrangente.test(endereco)) {
        elem.style.border = allright;
        elem.setAttribute("placeholder", '');
        return true;
    } else {
        elem.style.border = allwrong;
        elem.setAttribute("placeholder", 'Digite um email válido');
        return false;
    }  
}

function loginEmpty() {
    let login = document.getElementById('emailLogin');
    let senha = document.getElementById('senhaLogin');

    if (login.value == "" || senha.value == "") {
        msgShow(1,0);
        return false;
    } else {
        return true;
    }
}

function coletaCheck() {
    let login = document.getElementById('login2');
    let senha = document.getElementById('password2');
    let confsenha = document.getElementById('confpass2');
    let email = document.getElementById('email2');
    let cnpj = document.getElementById('cnpj2');

    if (login.value.length > 3 && !verifySpace(login.value)) {
        login.style.border = allright;
        if (senha.value.length > 7) {
            senha.style.border = allright;
            if (senha.value == confsenha.value) {  
                confsenha.style.border = allright;
                if (emailCheck('email2')) {
                    email.style.border = allright;            
                    if (cpfCnpjCheck('cnpj2')) {
                        cnpj.style.border = allright;
                        cadastroTrue(2, login, senha, email, cnpj, "", "", "")
                    } else {
                        cnpj.style.border = allwrong;
                        msgShow(26,0);
                        cnpj.focus();
                        return false;
                    }
                } else {
                    email.style.border = allwrong;
                    msgShow(27,0);
                    email.focus();
                    return false;
                }
            } else {
                confsenha.style.border = allwrong;
                msgShow(2, 0)
                confsenha.focus();
                return false;
            }
        } else {
            senha.style.border = allwrong;
            msgShow(10, 0)
            senha.focus();
            return false;
        }        
    } else {
        login.style.border = allwrong;
        msgShow(9, 0)
        login.focus();
        return false;
    }
}

function descarteCheck() {
    let empresa = document.getElementById('empresa');
    let pessoa = document.getElementById('pessoa');

    let login = document.getElementById('login');
    let senha = document.getElementById('password');
    let confsenha = document.getElementById('confpass');
    let email = document.getElementById('email');
    let nome = document.getElementById('nome');
    let cpf = document.getElementById('cpf');
    let cnpj = document.getElementById('cnpj');
    let estado = document.getElementById('estados');
    let cidade = document.getElementById('cidades');

    if (empresa.checked) {
        cpf_cnpj = cnpj;
    } else if (pessoa.checked) {
        cpf_cnpj = cpf;
    }
        
    if (login.value.length > 3 && !verifySpace(login.value)) {
        login.style.border = allright;
        if (senha.value.length > 7) {
            senha.style.border = allright;
            if (senha.value == confsenha.value) {
                confsenha.style.border = allright;
                if (pessoa.checked) {
                    if (emailCheck('email')) {
                        email.style.border = allright;
                        if (nome.value.length > 1) {
                            nome.style.border = allright;
                            if (cpfCnpjCheck('cpf')) {
                                cpf.style.border = allright;
                                if (estado.value != "") {
                                    cadastroTrue(1, login, senha, email, cpf_cnpj, nome, estado, cidade)
                                } else {
                                    estado.style.border = allwrong;
                                    msgShow(12,0);
                                    estado.focus();
                                    return false;
                                }
                            } else {
                                cpf.style.border = allwrong;
                                msgShow(11,0);
                                cpf.focus();
                                return false;
                            }
                        } else {
                            nome.style.border = allwrong;
                            msgShow(8,0);
                            nome.focus();
                            return false;
                        }
                    } else {
                        email.style.border = allwrong;
                        msgShow(27,0);
                        email.focus();
                        return false;
                    }
                } else if (empresa.checked) {
                    if (emailCheck('email')) {
                        if (cpfCnpjCheck('cnpj')) {
                            cadastroTrue(0, login, senha, email, cpf_cnpj, nome, estado, cidade)
                        } else {
                            cnpj.style.border = allwrong;
                            msgShow(26,0);
                            cnpj.focus();
                            return false;
                        }
                    } else {
                        email.style.border = allwrong;
                        msgShow(27,0);
                        email.focus();
                        return false;
                    }
                }                
            } else {
                confsenha.style.border = allwrong;
                msgShow(2, 0)
                confsenha.focus();
                return false;
            }
        } else {
            senha.style.border = allwrong;
            msgShow(10, 0)
            senha.focus();
            return false;
        }
    } else {
        login.style.border = allwrong;
        msgShow(9, 0)
        login.focus();
        return false;
    }
}

function cadastroTrue(tipo, login, senha, email, cpf_cnpj, nome, estado, cidade) {
    loadingPage(0);
    login = login.value;
    senha = senha.value;
    email = email.value;
    cpf_cnpj = cpf_cnpj.value;
    nome   = tipo == 0 || tipo == 2 ? "none" : nome.value;
    estado = tipo == 0 || tipo == 2 ? "none" : estado.value;
    cidade = tipo == 0 || tipo == 2 ? "none" : cidade.value;

    let parametros = {
        'tipo': tipo,
        'nome': nome,
        'login': login,
        'email': email,
        'senha': senha,
        'estado': estado,
        'cidade': cidade,
        'cpf_cnpj': cpf_cnpj,
        'enviar': 'enviar'
    };
    
    loadingPage(99);
    const verifyRegister = async () => {
        let data = await fetchServer("verificar_dados_cadastro", parametros);
        loadingPage(100);
        let length = data.substr(0, 18);
        if (length == "msgShow(15,1,1900)") {
            localStorage.setItem('msgHtml', data);
            let regex = /(<id>)(.*)(<\/id>)/;
            let currentId = regex.exec(data)[2];
            location.href = 'perfil.php?user=' + currentId;
        } else {
            data = "<script type='text/javascript'>"+data+"</script>";
            $('#msgShowHtml').html(data);
        }
    }
    verifyRegister();
}

async function updateCheck() {
    let login = document.getElementById('login');
    let password = document.getElementById('password');
    let passwordNew = document.getElementById('passwordNew');
    let confpass = document.getElementById('confpass');
    let nome = document.getElementById('nome');
    let email = document.getElementById('email');
    let estado = document.getElementById('estados');
    let cidade = document.getElementById('cidades');
    let cep = document.getElementById('cep');
    let bairro = document.getElementById('bairro');
    let rua = document.getElementById('rua');
    let numero = document.getElementById('numero');
    let complemento = document.getElementById('complemento');
    let telefone = document.getElementById('telefone');

    if (estado.value != "") {
        estado.style.border = allright;
        cidade.style.border = allright;
        if (cep.value != "" && cep.value.length == 8 && cepCheck()) {
            if (await cepCityCheck()) {
                cep.style.border = allright;
                if (numero.value != "") {
                    numero.style.border = allright;
                    if (login.value.length > 3 && !verifySpace(login.value)) {
                        login.style.border = allright;
                        if (emailCheck('email')) {
                            email.style.border = allright;
                            if (password.value.length > 7 || password.value.length == 0) {
                                password.style.border = allright;
                                if (passwordNew.value.length > 7 || passwordNew.value.length == 0) {
                                    passwordNew.style.border = allright;
                                    if (passwordNew.value == confpass.value || confpass.value.length == 0) {
                                        confpass.style.border = allright;
                                        if (nome.value.length > 1) {
                                            nome.style.border = allright;
                                            updateCheckTrue(login, password, passwordNew, nome, email, estado, cidade, cep, bairro, rua, numero, complemento, telefone);
                                        } else {
                                            nome.style.border = allwrong;
                                            msgShow(8,0);
                                            nome.focus();
                                            return false;
                                        }
                                    } else {
                                        confpass.style.border = allwrong;
                                        msgShow(2, 0)
                                        confpass.focus();
                                        return false;
                                    }
                                } else {
                                    passwordNew.style.border = allwrong;
                                    msgShow(10, 0)
                                    passwordNew.focus();
                                    return false;
                                }
                            } else {
                                password.style.border = allwrong;
                                msgShow(10, 0);
                                password.focus();
                                return false;
                            }
                        } else {
                            email.style.border = allwrong;
                            msgShow(27,0);
                            email.focus();
                            return false;
                        }
                    } else {
                        login.style.border = allwrong;
                        msgShow(9, 0)
                        login.focus();
                        return false;
                    }
                } else {
                    numero.style.border = allwrong;
                    msgShow(1, 0)
                    numero.focus();
                    return false;
                }
            } else {
                cep.style.border = allwrong;
                cidade.style.border = allwrong;
                estado.style.border = allwrong;
                msgShow(39,0);
                cep.focus();
                return false;
            }
        } else {
            cep.style.border = allwrong;
            msgShow(33,0);
            cep.focus();
            return false;
        }
    } else {
        estado.style.border = allwrong;
        cidade.style.border = allwrong;
        msgShow(12,0);
        estado.focus();
        return false;
    }
}

function updateCheckTrue(login, password, passwordNew, nome, email, estado, cidade, cep, bairro, rua, numero, complemento, telefone) {
    loadingPage(0);

    let saveButton = document.getElementById('salvarAlteracoes');
    let estadoAtuacao = document.getElementsByClassName('estadoAtuaClass');
    let cidadeAtuacao = document.getElementsByClassName('cidadeAtuaClass');
    let estadoAtuacaoArr = [];
    let cidadeAtuacaoArr = [];
    saveButton.disabled = true;
    for (let i = 0; i < estadoAtuacao.length; i++) {
        estadoAtuacaoArr.push(estadoAtuacao[i].value);
    }
    for (let i = 0; i < cidadeAtuacao.length; i++) {
        cidadeAtuacaoArr.push(cidadeAtuacao[i].value);
    }
    estadoAtuacao = JSON.stringify(estadoAtuacaoArr);
    cidadeAtuacao = JSON.stringify(cidadeAtuacaoArr);

    var parametros = {
        'login': login.value,
        'password': password.value,
        'passwordNew':passwordNew.value,
        'nome': nome.value,
        'email': email.value,
        'estado': estado.value,
        'cidade': cidade.value,
        'cep': cep.value,
        'bairro': bairro.value,
        'rua': rua.value,
        'numero': numero.value,
        'complemento': complemento.value,
        'telefone': telefone.value,
        'estadoAtuacao': estadoAtuacao,
        'cidadeAtuacao': cidadeAtuacao,
        'enviar': 'enviar'
    };

    loadingPage(99);
    const verifyConfig = async () => {
        const data = await fetchServer("verificar_dados_config", parametros);
        loadingPage(100);
        localStorage.setItem('msgHtml', data);
        location.reload();
    }
    verifyConfig();
}

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

async function configSaveDisabled() {
    $(document).ready(async function() {
        let saveButton = document.getElementById('salvarAlteracoes');
        await sleep(500);
        saveButton.disabled = false;
    });
}

async function verifyHtmlMsg() {
    if (localStorage.getItem('msgHtml') !== null) {
        let data = localStorage.getItem('msgHtml');
        data = "<script type='text/javascript'>"+data+"</script>";
        $('#msgShowHtml').html(data);
        await sleep(100);
        localStorage.removeItem('msgHtml');
    }    
}

async function loadingPage(newprogress) {
    let progress = document.getElementById('progress')
    if (newprogress == 0) {
        progress.style.display = "flex";
    }
    //$('html,body').scrollTop(0);
    $('#progress-bar').attr('aria-valuenow', newprogress).css('width', newprogress+'%');
    if (newprogress == 100) {
        await sleep(500);
        progress.style.display = "none";
    }
}

function nameCheck(id) {
    var elem = document.getElementById(id);

    if (elem.value.length > 1) {
        elem.style.border = allright;
        elem.setAttribute("placeholder", '');
    } else {
        elem.style.border = allwrong;
        elem.setAttribute("placeholder", 'Mínimo de 2 caracteres');
    }
}

function loginCheck(id) {
    var elem = document.getElementById(id);

    if (elem.value.length > 3 && !verifySpace(elem.value)) {
        elem.style.border = allright;
        elem.setAttribute("placeholder", '');
    } else {
        elem.style.border = allwrong;
        elem.setAttribute("placeholder", 'Min 4 letras sem espaço');
    }
}

function passCheck(id) {
    var elem = document.getElementById(id);

    if (elem.value.length > 7) {
        elem.style.border = allright;
        elem.setAttribute("placeholder", '');
    } else {
        elem.style.border = allwrong;
        elem.setAttribute("placeholder", 'Mínimo de 8 caracteres');
    }
}

function confPassCheck(id1, id2) {
    var elem1 = document.getElementById(id1);
    var elem2 = document.getElementById(id2);

    if (elem1.value == elem2.value && elem2.value.length > 7) {
        elem2.style.border = allright;
        elem2.setAttribute("placeholder", '');
    } else {
        elem2.style.border = allwrong;
        elem2.setAttribute("placeholder", 'Senhas precisam ser iguais');
    }
}

function passCheckVerConfig(id) {
    var password = document.getElementById('password');
    var passwordNew = document.getElementById('passwordNew');
    var confpass = document.getElementById('confpass');
    var elem = document.getElementById(id);
    if (confpass.value == "" && passwordNew.value == "" && password.value == "") {
        confpass.readOnly = true;
        passwordNew.readOnly = true;
        confpass.style.border = allclear;
        passwordNew.style.border = allclear;
    } else {
        confpass.readOnly = false;
        passwordNew.readOnly = false;
    }

    if (elem.value.length > 7 || elem.value == "") {
        elem.style.border = allright;
        elem.setAttribute("placeholder", '');
    } else {
        elem.style.border = allwrong;
        elem.setAttribute("placeholder", 'Mínimo de 8 caracteres');
    }
}

function confPassCheckVerConfig(id1, id2) {
    var elem1 = document.getElementById(id1);
    var elem2 = document.getElementById(id2);

    if ((elem1.value == elem2.value && elem2.value.length > 7) || elem2.value == "") {
        elem2.style.border = allright;
        elem2.setAttribute("placeholder", '');
    } else {
        elem2.style.border = allwrong;
        elem2.setAttribute("placeholder", 'Senhas precisam ser iguais');
    }
}
// (script filename(PHP), parâmetros(objeto), method(GET/POST))
async function fetchServer(script = null, param = {}, method = "POST", timeout = 10) {
    let body = new FormData();
    for (let attr in param) {
        body.append(attr, param[attr]);
    }
    if (script != null) {
        script = `php/${script}.php`;
        const data = await fetch(script, { method, body, timeout })
        .then(async response => {
            if (!response.ok) {
                const status = await response.status;
                if (status == 200) {
                    console.log("Error: Server offline");
                    return false;
                }
                if (status == 404) {
                    console.log("Error: PHPScript not found");
                    return false;
                }
            } else {
                const data = await response.text();
                return data;
            }
        });
        return data;
    } else {
        console.log("Error: PHPScript not found, are you missing the filename?");
        return false;
    }   
}

var removedIds = []
async function addAtuacao(estado = 0, cidade = 0) {
    const atuacoesElem = $("#atuacoes");
    let max = atuacoesElem.data("max") || 4;
    //let removedIds = atuacoesElem.data("removedIds") || [];
    let id = -1;
    let initNum = -1;

    if (removedIds.length != 0) {
        id = removedIds.pop();
    } else {
        $("#atuacoes").children().each(function(i, elm) {
            let divID = $(this).data("id");
            if (divID > initNum) {
                id = divID;
                initNum = divID;
            } else {
                initNum = divID;
            }
        });
        id++
    }    

    let localeDiv = $(`<div class="row cad" data-id=${id} data-locale="${estado}-${cidade}"></div>`);
    let localeEstado = `<span class="col-5 mobileAtuaState"><select class="custom-select mr-sm-2 regiao estadoAtuaClass" id=estadoAtuacao${id}></select></span>`;
    let localeCidade = `<span class="col-6 mobileAtuaCity"><select class="custom-select mr-sm-2 regiao cidadeAtuaClass" id=cidadeAtuacao${id}></select></span>`;
    let removeButton = $(`<span class="col-1 configLocaleCol"><a class="configLocale"><i class="fa fa-minus" aria-hidden=true></i></a></span>`);

    removeButton.click(async function() {
        let parentElem = $(this).parent();
        let locale = parentElem.data("locale");
        let id = parentElem.data("id");
        let data = await fetchServer("verificar_exclusao_atuacao", {"rmvAtuacao": locale});
        if (data != 1) {
            localStorage.setItem('msgHtml', data);
            verifyHtmlMsg();
        } else {
            removedIds.push(id);
            parentElem.remove();
        }
    });

    // if (id >= max) {
    //     max = await fetchServer("verificar_max_atuacao");
    //     atuacoesElem.data("max", max);
    // }
    if (id <= max) {
        localeDiv.append(localeEstado);
        localeDiv.append(localeCidade);
        localeDiv.append(removeButton);
        atuacoesElem.append(localeDiv);

        $.getJSON('js/estados_cidades.json', function (data) {
            let options = `<option value="">Estados</option>`;
            $.each(data, function (key, val) {
                options += `<option data-state=${key} value="${val.nome}">${val.nome}</option>`;
            });

            $(`#estadoAtuacao${id}`).html(options);    
            $(`#estadoAtuacao${id}`).change(function () {
                let options_cidades = '';
                let str = "";
                $(`#estadoAtuacao${id} option:selected`).each(function () {
                    str += $(this).text();
                });
                $.each(data, function (key, val) {
                    if(val.nome == str) {
                        $.each(val.cidades, function (key_city, val_city) {
                            options_cidades += `<option data-locale=${key}-${key_city} value="${val_city}">${val_city}</option>`;
                        });
                    }
                });
                $(`#cidadeAtuacao${id}`).html(options_cidades);
            }).change();
        });
    } else {
        if (max == 4)
        msgShow(64, 2);
    }
    const verifyMax = async () => {
        if (id >= max) {
            max = await fetchServer("verificar_max_atuacao");
            atuacoesElem.data("max", max);
        }
    }
    verifyMax();
}

function savePassCheck() {
    let passwordNew = document.getElementById('senhaN');
    let confpass = document.getElementById('senhaNN');
    if (passwordNew.value.length > 7) {
        passwordNew.style.border = allright;
        if (passwordNew.value == confpass.value) {
            confpass.style.border = allright;
        } else {
            confpass.style.border = allwrong;
            msgShow(2, 0)
            confpass.focus();
            return false;
        }
    } else {
        passwordNew.style.border = allwrong;
        msgShow(10, 0)
        passwordNew.focus();
        return false;
    }  
}

function switchMode(mode) {
    let coleta = document.getElementById('coleta');
    let descarte = document.getElementById('descarte');
    let bcole = document.getElementById('button-cole');
    let bdesc = document.getElementById('button-desc');

    if (mode == 'coleta') {
        coleta.style.display="unset";
        descarte.style.display="none";
        bdesc.style.border="solid 2px gray"
        bcole.style.border="solid 2px rgb(30, 255, 98)"
    } else {
        coleta.style.display="none";
        descarte.style.display="unset";
        bcole.style.border="solid 2px gray"
        bdesc.style.border="solid 2px rgb(30, 255, 98)"
    }
}

function switchDescarteMode(id) {
    let empresasElem = document.getElementsByClassName('descarteEmpresa');
    let pessoasElem = document.getElementsByClassName('descartePessoa');
    
    switch (id) {
        case '0':
            for (var i = 0; i < empresasElem.length; i++) {
                var currentElement = empresasElem[i];
                currentElement.style.display="flex";
                currentElement.style.margin="10px -15px 0 -15px";
            }
            for (var i = 0; i < pessoasElem.length; i++) {
                var currentElement = pessoasElem[i];
                currentElement.style.display="none";
                currentElement.style.margin="0 -15px 0 -15px";
            }
        break;
        case '1':
            for (var i = 0; i < empresasElem.length; i++) {
                var currentElement = empresasElem[i];
                currentElement.style.display="none";
                currentElement.style.margin="0 -15px 0 -15px";
            }
            for (var i = 0; i < pessoasElem.length; i++) {
                var currentElement = pessoasElem[i];
                currentElement.style.display="flex";
                currentElement.style.margin="10px -15px 0 -15px";
            }
        break;
    }
}

function checkPassFill() {
    var password = document.getElementById('password');
    var passwordNew = document.getElementById('passwordNew');
    var confpass = document.getElementById('confpass');
    if (confpass.value == "" && passwordNew.value == "" && password.value == "") {
        confpass.readOnly = true;
        passwordNew.readOnly = true;
        confpass.style.border = allclear;
        passwordNew.style.border = allclear;
    }
}

function playNotify() {
    var audio = new Audio('sounds/notify_sound.mp3');
    audio.addEventListener('canplaythrough', function () {
        audio.volume = 0.09;
        audio.play();
    });
}

function descarteCall(descarteId) {
    location.href = 'descarte.php?id='+descarteId;
}

function itemColeta(descarteId, agendaId) {
    if (verifyList) {
        location.href = "descarte.php?id="+descarteId+"&agenda="+agendaId;
    }
}

function onlyTel(campo) {
    var evento = campo || window.event;
    var chave = evento.keyCode || evento.which;
    chave = String.fromCharCode(chave);
 
    var regra = /^[0-9\(\)\+\-\s\/]+$/;
 
    if (!regra.test(chave)) {
 
       evento.returnValue = false;
 
       if(evento.preventDefault)
         evento.preventDefault();
    }
}

function onlyNum(campo) {
    var evento = campo || window.event;
    var chave = evento.keyCode || evento.which;
    chave = String.fromCharCode(chave);
 
    var regra = /^[0-9\.\,\s\/\-]+$/;
 
    if (!regra.test(chave)) {
 
       evento.returnValue = false;
 
       if(evento.preventDefault)
         evento.preventDefault();
    }
}

function onlyCep(campo) {
    var evento = campo || window.event;
    var chave = evento.keyCode || evento.which;
    chave = String.fromCharCode(chave);
 
    var regra = /^[0-9]+$/;
 
    if (!regra.test(chave)) {
 
       evento.returnValue = false;
 
       if(evento.preventDefault)
         evento.preventDefault();
    }
}

String.prototype.ucwords = function() {
    str = this.toLowerCase();
    return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g, function(s){
        return s.toUpperCase();
    });
};

function removeAcento(text) {       
    text = text.toLowerCase();                                                         
    text = text.replace(new RegExp('[ÁÀÂÃ]','gi'), 'a');
    text = text.replace(new RegExp('[ÉÈÊ]','gi'), 'e');
    text = text.replace(new RegExp('[ÍÌÎ]','gi'), 'i');
    text = text.replace(new RegExp('[ÓÒÔÕ]','gi'), 'o');
    text = text.replace(new RegExp('[ÚÙÛ]','gi'), 'u');
    text = text.replace(new RegExp('[Ç]','gi'), 'c');
    return text;                 
}

async function cepCityCheck() {
    let cep = document.getElementById("cep").value;
    let data = await dadosCep(cep);
    var cidadeCampo = document.getElementById("cidades").value;
    var cidadeCep = data.localidade;

    if (cidadeCep != undefined) {
        cidadeCep = removeAcento(cidadeCep);
        cidadeCep = cidadeCep.ucwords();
    
        cidadeCampo = cidadeCampo.replace("-", " ");
        cidadeCampo = cidadeCampo.ucwords();
    
        if (cidadeCampo == cidadeCep)
            return true;
        else
            return false;
    }
}

async function dadosCep(cep) {
    const url = `https://viacep.com.br/ws/${cep}/json/`;
    let data = await fetch(url)
    .then( async (response) => {
        if (response.ok) {
            let dataReturn = await response.json();
            return dataReturn;
        }
    });
    return data;
}

function loadanimation(mode) {
    let elem = document.getElementById("loadbox");
    if (mode)
        elem.removeAttribute("style");
    else
        elem.setAttribute("style", 'display:none');
}

async function cepCheck() {
    var elemCep = document.getElementById("cep");
    var elemBairro = document.getElementById("bairro");
    var elemRua = document.getElementById("rua");
    var cep = elemCep.value;
    var erro;
    var data;
    var bairro;
    var rua;

    if (cep.length == 8) {
        data = await dadosCep(cep);
        
        bairro = data.bairro;
        rua = data.logradouro;
        erro = data.erro;
        
        if (erro == undefined)
            ok = true;
        else
            ok = false;
    } else {
        ok = false;
        elemCep.style.border = allwrong;
    }
    
    if (ok) {
        elemCep.style.border = allright;
        elemBairro.value = bairro;
        elemRua.value = rua;
        return true;
    } else {
        elemCep.style.border = allwrong;
        elemBairro.value = "";
        elemRua.value = "";
        return false;
    }
}

async function getJson(fileName) {
    const data = await fetch(`js/${fileName}.json`)
    .then(response => response.json())
    .then(json => json)
    .catch(err => {
        throw new Error("erro"+err)
    });
    return data;
}

async function systemPopup(id = null, value = null, list = null) {
    // 0: erro, 1: coletaConfirm, 2: coletaCancel, 3: agendaDelete, 4: adicionarAgenda,
    // 5: cancelColeta, 6: showDeleteAccConfirm, 7: showDeleteCardConfirm,
    // 8: welcome, 9: concluirColeta, 10: cardCreate, 11: selecionarCartao
    verifyList = false;
    const json = await getJson("systemPopup");
    let msg = {}
    let param = {}
    let add = {}
    let script = "";
    let data = "";
    let html = "";

    const fetchReload = async (script, param) => {
        data = await fetchServer(script, param);
        localStorage.setItem('msgHtml', data);
        location.reload();
    }

    if (id != null) {
        msg = json[id]
        if (msg != undefined) {
            delete msg.id;
        }

        switch (id) {
            case 0:
                msg = json[0];
            break;
            case 1: case 2: case 8:
                script = "verificar_coleta";
                param = {
                    descarteId: value,
                    notifyUndo: 1
                }
                add = {
                    onClose: async () => {
                        await fetchServer(script, param);
                    },
                    preConfirm: async () => {
                        await fetchServer(script, param);
                    }
                }
            break;
            case 3:
                script = "verificar_exclusao_agenda";
                param = {
                    agendaId: value
                }
                html = msg.html;
                html = html.replace("${placeholder}", value)
                add = {
                    html,
                    inputAttributes: { autocapitalize: "off" },
                    preConfirm: async () => {
                        await fetchReload(script, param);
                    }
                }
            break;
            case 4:
                script = "criar_agenda";
                param = {
                    criar: null
                }
                add = {
                    preConfirm: async () => {
                        await fetchReload(script, param);
                    }
                }
            break;
            case 5:
                script = "verificar_cancel_coleta";
                param = {
                    descarteId: value,
                    cancelColeta: true
                };
                add = {
                    preConfirm: async () => {
                        await fetchReload(script, param);
                    }
                }
            break;
            case 6:
                script = "verificar_exclusao_conta";
                add = {
                    input: 'password',
                    inputAttributes: { autocapitalize: 'off' },
                    preConfirm: async (senha) => {
                        param = {
                            id: value,
                            senha: senha
                        }
                        await fetchReload(script, param);
                    }
                }
            break;
            case 7:
                script = "verificar_exclusao_cartao";
                param = {
                    removeCard: value
                }
                html = msg.html;
                html = html.replace("${placeholder}", value);
                add = {
                    html,
                    preConfirm: async () => {
                        param = {
                            removeCard: value
                        }
                        await fetchReload(script, param);
                    }
                }
            break;
            case 9:
                script = "concluir_coleta";
                param = {
                    descarte: value
                }
                add = {
                    preConfirm: async () => {
                        await fetchReload(script, param);
                    },
                    onClose: () => {
                        verifyList = true;
                    }
                }
            break;
            case 10:
                oilBar();
                script = "verificar_add_cartao";
                add = {
                    preConfirm: async () => {
                        let descricao = document.getElementById('descricao');
                        let saida = document.getElementById('saida');
                        param = {
                            descricao: descricao.value,
                            saida: saida.value,
                            registrarDescarte: 1
                        }
                        data = await fetchServer(script, param);
                        localStorage.setItem('msgHtml', data);
                        if (data != "msgShow(17,1);") {
                            verifyHtmlMsg();
                        } else {
                            location.reload();
                        }
                    }
                }
            break;
            case 11:
                script = "verificar_add_agenda";
                add = {
                    preConfirm: async () => {
                        if (list == "") {
                            location.href="meus_descartes.php";
                        } else {
                            let descarteElem = document.getElementById('listOfDescartes');
                            param = {
                                descarte: descarteElem.value,
                                agenda: value,
                                addAgenda: true
                            }
                            data = await fetchServer(script, param);
                            localStorage.setItem('msgHtml', data);
                            if (data == "msgShow(63, 0)") {
                                location.href = "procurar_agendas.php?p=0";
                            } else {
                                location.reload();
                            }
                        }
                    }
                }
                if (list == "") {
                    Object.assign(msg, add)
                    html = msg.alternativeHtml;

                    let showCancelButton = false;
                    let confirmButtonText = 'Criar';
                    add = {
                        html, showCancelButton,
                        confirmButtonText
                    }
                } else {
                    Object.assign(msg, add)
                    html = msg.html;
                    html = html.replace("${placeholder}", list);
                    add = {
                        html
                    }
                }
                delete msg.alternativeHtml;
            break;
            default:
                msg = json[0];
                delete msg.id;
            break;
        }
        Object.assign(msg, add)
        add = {
            allowOutsideClick: () => !Swal.isLoading(),
            showLoaderOnConfirm: true
        }
        Object.assign(msg, add)
    }
    Swal.fire(msg);
    verifyList = true;
}

//msgShow(ID da MSG, Tipo de icon, Tempo de exibição(em milisegundos), Posição da exibição)
//'top', 'top-start', 'top-end', 'center', 'center-start', 'center-end', 'bottom', 'bottom-start', or 'bottom-end'
//customB = custom msg before, customA = custom msg after, customZ = qualquer informação que queira passar
//showToast = altera entre modo toast e modo static, progressBar = altera entre contagem de desaparecer, confirmButton = altera entre com botão ou sem
async function msgShow(id = null, type = null, timer = 6000, position = 'bottom-end', customB = "", customA = "", toast = true, timerProgressBar = true, showConfirmButton = false, customZ = "") {
    let msg, icon, mensagem, corpo, html, title;

    const json = await getJson("msgShow");

    msg = json[id];

    if (msg != undefined) {
        msg = json[id].msg;        
    } else {
        msg = "Erro Desconhecido";
    }
    
    if (id == 30 || id == 55) {
        corpo = msg;
        msg = "";
        html = "<a href='configuracoes.php'><span class='customMsg30'>" + corpo + "</span></a>";
    }

    if (id == 31 || id == 34 || id == 35 || id == 56 || id == 57 || id == 54 || id == 66) {
        corpo = msg;
        msg = "";
        html = corpo + customZ;
    }

    icon = type == 0 ? 'error' : null;
    icon = type == 1 ? 'success' : icon;
    icon = type == 2 ? 'warning' : icon;
    icon = type == 3 ? 'info' : icon;
    icon = type == 4 ? 'question' : icon;

    icon = icon == null ? 'error' : icon;

    title = customB + msg + customA;

    mensagem = {
        position, icon, title,
        showConfirmButton, html,
        timer, timerProgressBar,
        toast, padding: "0.8rem 2rem",
        onOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        },
        onClose: () => {
            //whatever I want to do when the toast closed
        }
    };

    Swal.fire(mensagem);
}


function validaCpfCnpj(val) {
    val = val.trim();
    val = val.replace(/\./g, '');
    val = val.replace('-', '');
    val = val.replace('/', ''); 
    val = val.split(''); 

    if (val.length == 11) {        
        let cpf = val;
        let v1 = 0;
        let v2 = 0;
        let aux = false;
        
        for (var i = 1; cpf.length > i; i++) {
            if (cpf[i - 1] != cpf[i])
                aux = true;            
        } 
        
        if (aux == false)
            return false; 
        
        for (var i = 0, p = 10; (cpf.length - 2) > i; i++, p--) {
            v1 += cpf[i] * p; 
        } 
        
        v1 = ((v1 * 10) % 11);
        
        if (v1 == 10)
            v1 = 0; 
        
        if (v1 != cpf[9])
            return false; 
        
        for (var i = 0, p = 11; (cpf.length - 1) > i; i++, p--) {
            v2 += cpf[i] * p; 
        } 
        
        v2 = ((v2 * 10) % 11);
        
        if (v2 == 10)
            v2 = 0; 
        
        if (v2 != cpf[10])
            return false;
        else
            return true;

    } else if (val.length == 14) {  
        let cnpj = val;      
        let v1 = 0;
        let v2 = 0;
        let aux = false;
        
        for (var i = 1; cnpj.length > i; i++) { 
            if (cnpj[i - 1] != cnpj[i])
                aux = true;
        } 
        
        if (aux == false)
            return false; 
        
        for (var i = 0, p1 = 5, p2 = 13; (cnpj.length - 2) > i; i++, p1--, p2--) {
            if (p1 >= 2)
                v1 += cnpj[i] * p1;
            else
                v1 += cnpj[i] * p2;
        } 
        
        v1 = (v1 % 11);
        
        if (v1 < 2)
            v1 = 0; 
        else
            v1 = (11 - v1); 
        
        if (v1 != cnpj[12])
            return false; 
        
        for (var i = 0, p1 = 6, p2 = 14; (cnpj.length - 1) > i; i++, p1--, p2--) { 
            if (p1 >= 2)
                v2 += cnpj[i] * p1;  
            else
                v2 += cnpj[i] * p2; 
        }
        
        v2 = (v2 % 11); 
        
        if (v2 < 2)
            v2 = 0;
        else
            v2 = (11 - v2);
        
        if (v2 != cnpj[13])
            return false; 
        else
            return true; 
    } else
        return false;
}

jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        //window.location = $(this).data("href");
    });
});

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

function oilBar() {
    // var isFF = true;
    // var addRule = (function (style) {
    //   var sheet = document.head.appendChild(style).sheet;
    //   return function (selector, css) {
    //     if ( isFF ) {
    //       if ( sheet.cssRules.length > 0 ) {
    //         sheet.deleteRule( 0 );
    //       }
    //       try {
    //         sheet.insertRule(selector + "{" + css + "}", 0);
    //       } catch ( ex ) {
    //         isFF = false;
    //       }
    //     }    
    //   };
    // })(document.createElement("style"));
    $(document).ready(function() {
        $('.rangeOleo').on('input', function() {
            var porc = this.value*4.44;
            var porc2 = this.value;
            
            $('.textOleo').css('transform', 'translate(calc(' + porc + 'px + -222px), 0px)');
            $(this).css( 'background', 'linear-gradient(to right,  #00d800 0%,  #00d800 '+ porc2 +'%, #ccc ' + porc2 + '%, #ccc 100%)');
        });
    });
}
