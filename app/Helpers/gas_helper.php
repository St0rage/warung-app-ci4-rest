<?php

function updateTotal($gas_id)
{
    $db = \Config\Database::connect();

    $gas_builder = $db->table('gas');
    $gas_note_builder = $db->table('gas_note');

    // get_gas_price
    $gas_builder->select('price');
    $gas_builder->where('id', $gas_id);
    $getGasPrice = $gas_builder->get()->getRow();
    
    // get_all_gas_notes
    $gas_note_builder->where('gas_id', $gas_id);
    $getAllNotes = $gas_note_builder->get()->getResultArray();

    if ($getAllNotes) {
        foreach($getAllNotes as $notes) {
            $price = $notes['quantity'] * $getGasPrice->price;

            $notes['total'] = $price;

            // update_total
            $gas_note_builder->replace($notes);
        }
    } else {
        return false;
    }
}