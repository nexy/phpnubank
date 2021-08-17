<?php
namespace Nubank\Utils;

class PixConstants
{
    const TITLE_INFLOW_PIX = 'Transferência recebida';
    const TITLE_OUTFLOW_PIX = 'Transferência enviada';
    const TITLE_REVERSAL_PIX = 'Reembolso enviado';
    const TITLE_FAILED_PIX = 'Transferência falhou';

    static public function pixTransactionMap()
    {
      return [
        TITLE_INFLOW_PIX => 'PixTransferInEvent',
        TITLE_OUTFLOW_PIX => 'PixTransferOutEvent',
        TITLE_REVERSAL_PIX => 'PixTransferOutReversalEvent',
        TITLE_FAILED_PIX => 'PixTransferFailedEvent',
      ];
    }
}