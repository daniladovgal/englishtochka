document.getElementById('popup_show').addEventListener("click", showpopup);
document.getElementById('popup_hide').addEventListener("click", hidepopup);

function showpopup() {
    document.getElementById("popup").style.display = "flex";
}

function hidepopup() {
    document.getElementById("popup").style.display = "none";
}

var btns = document.querySelectorAll('.btn[name="order"]');
for ( btn of btns ) {
    btn.onclick = function() {
        order(this.id.substring(5));
    };
}

function order(id) {
    var request = new XMLHttpRequest();
    request.open('GET', 'api/?data.add={"login":"test1","productid":"' + id + '"}');
    request.setRequestHeader('Content-Type', 'application/x-www-form-url');
    request.addEventListener("readystatechange", () => {
        if (request.readyState === 4 && request.status === 200) {
            var ans = JSON.parse(request.responseText);
            if (ans.response == "Success") {
                alert("Скидка активирована");
            } else {
                if (ans.response == "Row is exist") {
                    alert("Скидка уже активирована");
                } else {
                    alert("Не удалось активировать скидку");
                }
            }
        }
        loading = false;
    });
    request.send();
}

document.getElementById('data_get').addEventListener("click", getdata);

var loading = false;

function getdata() {
    if (loading != true) {
        loading = true;
        var login = document.getElementById('login').value;
        if (login == "" || login == undefined || login == null) {
            alert("Заполните поле");
        } else {
            var request = new XMLHttpRequest();
            request.open('GET', 'api/?data.get={"login":"' + login + '"}');
            request.setRequestHeader('Content-Type', 'application/x-www-form-url');
            request.addEventListener("readystatechange", () => {
                if (request.readyState === 4 && request.status === 200) {
                    var ans = JSON.parse(request.responseText).response;
                    if (ans.id != undefined) {
                        var html = `id: ${ans.id} <br> login: ${ans.login} <br> name: ${ans.name} <br> coins: ${ans.coins} <br>`;
                        ans.products.forEach(function(product, i) {
                            var buy = false;
                            if (product.product_id != null) {
                                buy = true;
                            }
                            html = html + `productid: ${product.id} description: ${product.description} buy: ${buy} <br>`;
                        });
                        document.getElementById('info').innerHTML = html;
                    } else {
                        alert(ans);
                    }
                }

                loading = false;
            });
            request.send();
        }
    }
}