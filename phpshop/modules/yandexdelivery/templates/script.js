function yandexdeliveryStart() {
    $('input[name="yadelivery_sum"]').remove();
    $('input[name="yadelivery_info"]').remove();
    $('input[name="yadelivery_type"]').remove();
    $('input[name="yadelivery_pvz_id"]').remove();
    $('input[name="yadelivery_tariff_id"]').remove();
    $('input[name="yadelivery_partner_id"]').remove();

    $('<input type="hidden" name="yadelivery_sum">').insertAfter('#dop_info');
    $('<input type="hidden" name="yadelivery_info">').insertAfter('#dop_info');
    $('<input type="hidden" name="yadelivery_type">').insertAfter('#dop_info');
    $('<input type="hidden" name="yadelivery_pvz_id">').insertAfter('#dop_info');
    $('<input type="hidden" name="yadelivery_tariff_id">').insertAfter('#dop_info');
    $('<input type="hidden" name="yadelivery_partner_id">').insertAfter('#dop_info');

    var PHPShopYandexDeliveryInstance = new PHPShopYandexDelivery();
    PHPShopYandexDeliveryInstance.init({
        api: $('input[name="yandex_delivery_api"]').val(),
        sender: $('input[name="yandex_delivery_sender_id"]').val(),
        warehouse: $('input[name="yandex_delivery_warehouse_id"]').val(),
        orderUid: $('input[name="yandex_delivery_order_uid"]').val(),
        cart: $.parseJSON($('input[name="yandex_delivery_cart"]').val())
    });

    PHPShopYandexDeliveryInstance.openWidget();
}

PHPShopYandexDelivery = function () {
    var self = this;

    self.api;
    self.sender;
    // Начальные данные
    self.cart = {
        places: [
            {
                externalId: null,
                items: []
            },
        ],
        shipment: {
            fromWarehouseId: null
        },
        cost: {
            fullyPrepaid: false
        },
        deliveryTypes: ['PICKUP', 'COURIER']
    };

    self.init = function (params) {
        self.api = params.api;
        self.sender = params.sender;
        self.cart.shipment.fromWarehouseId = params.warehouse;
        self.cart.places[0].externalId = params.orderUid;
        self.cart.places[0].items = params.cart;
    };

    self.openWidget = function () {
        YaDelivery.createWidget({
            containerId: 'phpshopYaDeliveryWidget',
            type: 'deliveryCart',
            params: {
                apiKey: self.api,
                senderId: self.sender
            }
        }).then(self.onOpenSuccess).catch(self.onOpenFailure);
    };

    self.onOpenSuccess = function (widget) {
        widget.showDeliveryOptions(self.cart);
        widget.on('submitDeliveryOption', function (deliveryOption) {
            $("#DosSumma").html(deliveryOption['deliveryOption']['cost']['deliveryForCustomer']);
            $("#TotalSumma").html(Number(deliveryOption['deliveryOption']['cost']['deliveryForCustomer']) + Number($('#OrderSumma').val()));
            $('input[name="yadelivery_sum"]').val(deliveryOption['deliveryOption']['cost']['deliveryForCustomer']);
            $('input[name="yadelivery_tariff_id"]').val(deliveryOption['deliveryOption']['tariffId']);
            $('input[name="yadelivery_partner_id"]').val(deliveryOption['deliveryOption']['partner']);

            if(deliveryOption['deliveryType'] === 'COURIER') {
                $('#deliveryInfo').html('Курьерская доставка: ' + deliveryOption['deliveryService']['name']);
                $('input[name="yadelivery_info"]').val('Курьерская доставка: ' + deliveryOption['deliveryService']['name']);
                $('input[name="yadelivery_type"]').val('COURIER');
            }
            if(deliveryOption['deliveryType'] === 'PICKUP') {
                $('#deliveryInfo').html('ПВЗ: ' + deliveryOption['deliveryService']['name'] + ' ' + deliveryOption['pickupPoint']['address']['addressString']);
                $('input[name="yadelivery_info"]').val('ПВЗ: ' + deliveryOption['deliveryService']['name'] + ' ' + deliveryOption['pickupPoint']['address']['addressString']);
                $('input[name="yadelivery_type"]').val('PICKUP');
                $('input[name="yadelivery_pvz_id"]').val(deliveryOption['pickupPoint']['id']);
            }
        });
    };

    self.onOpenFailure = function (error) {
        console.log(error);
    };
};