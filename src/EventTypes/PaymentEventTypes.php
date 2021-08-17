<?php
namespace Nubank\EventTypes;

class PaymentEventTypes
{
    static public function all()
    {
      return [
          'TransferOutEvent',
          'TransferInEvent',
          'TransferOutReversalEvent',
          'BarcodePaymentEvent',
          'DebitPurchaseEvent',
          'DebitPurchaseReversalEvent',
          'BillPaymentEvent',
          'DebitWithdrawalFeeEvent',
          'DebitWithdrawalEvent',
          'PixTransferOutEvent',
          'PixTransferInEvent',
          'PixTransferOutReversalEvent',
          'PixTransferFailedEvent',
      ];
  }
}