import "./bootstrap";

import jQuery from "jquery";
import moment from "moment";

window.$ = window.jQuery = jQuery;
window.moment = moment;

$(document).ready(function () {
    window.toast = $(".notif");
    window.alert = $(".alert-container");
    window.alertFilter = $(".alert-filter-container");

    window.debounce = function (func, timeout = 300) {
        let timer;
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => {
                func.apply(this, args);
            }, timeout);
        };
    };


    $("#alert-close").on("click", function () {
        $("#alert-dismiss").slideUp(1000);
        sessionStorage.setItem("menu-info", JSON.stringify(false));
    });

    window.getBalance = function() {
        $.ajax({
            type: "GET",
            url: "http://127.0.0.1:8000/balance",
            beforeSend: function () {},
        })
            .done(function (response) {
                if (response.status) {
                    let balance = formatCurrency(response.data.balance);
                    $("#balance").html(balance);
                } else {
                }
            })
            .fail(function (response) {
                console.log(response);
            });
    }
});

window.capitalize = function (s) {
    return s[0].toUpperCase() + s.slice(1);
};

window.formatRupiah = function (angka, prefix) {
    var number_string = angka.replace(/[^,\d]/g, "").toString(),
        split = number_string.split(","),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (split[1]) {
        if (split[1].length > 3) {
            return 0;
        }
    }

    if (ribuan) {
        let separator = sisa ? "." : "";
        rupiah += separator + ribuan.join(".");
    }

    rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
    return prefix == undefined ? rupiah : rupiah ? "Rp. " + rupiah : "";
};

window.formatCurrency = function (value) {
    let rupiah = new Intl.NumberFormat("en-DE", {
        style: "currency",
        currency: "IDR",
    });
    let formatted = rupiah.format(value);
    formatted = formatted.replace("IDR", "").replace(",", "#").replace(".", ",").replace("#", ".");
    return formatted;
};
