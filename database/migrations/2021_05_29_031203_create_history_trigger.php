<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateHistoryTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        
        CREATE TRIGGER kas_masuk_history 
        AFTER INSERT ON kas_masuk 
        FOR EACH ROW BEGIN  

        INSERT INTO saldo_history(kode_transaksi, type,jumlah,saldo_akhir,keterangan,created_at)  
        VALUES(new.kode_transaksi,"INCOME",new.jumlah,(select saldo from saldo limit 1)+new.jumlah,new.keterangan,SYSDATE());

        UPDATE `saldo` SET `saldo`=(select saldo from saldo limit 1)+new.jumlah WHERE id =1;

        END
        
        ');

        DB::unprepared('
        CREATE TRIGGER kas_keluar_history 
        AFTER INSERT ON kas_keluar 
        FOR EACH ROW BEGIN  

        INSERT INTO saldo_history(kode_transaksi, type,jumlah,saldo_akhir,keterangan,created_at)  
        VALUES(new.kode_transaksi,"OUTCOME",new.jumlah,(select saldo from saldo limit 1)-new.jumlah,new.keterangan,SYSDATE());
        UPDATE `saldo` SET `saldo`=(select saldo from saldo limit 1)-new.jumlah WHERE id =1;

        END
        ');

        DB::unprepared('
        CREATE TRIGGER kas_hutang_history 
        AFTER INSERT ON hutangs 
        FOR EACH ROW BEGIN  

        INSERT INTO saldo_history(kode_transaksi, type,jumlah,saldo_akhir,keterangan,created_at)  
        VALUES(new.kode_transaksi,"HUTANG",new.jumlah,(select saldo from saldo limit 1)-new.jumlah,new.keterangan,SYSDATE());
        UPDATE `saldo` SET `saldo`=(select saldo from saldo limit 1)+new.jumlah WHERE id =1;

        END
        ');

        DB::unprepared('
        CREATE TRIGGER kas_piutang_history 
        AFTER INSERT ON piutangs 
        FOR EACH ROW BEGIN  

        INSERT INTO saldo_history(kode_transaksi, type,jumlah,saldo_akhir,keterangan,created_at)  
        VALUES(new.kode_transaksi,"PIUTANG",new.jumlah,(select saldo from saldo limit 1)-new.jumlah,new.keterangan,SYSDATE());
        UPDATE `saldo` SET `saldo`=(select saldo from saldo limit 1)-new.jumlah WHERE id =1;

        END
        ');


        DB::unprepared('
        CREATE TRIGGER kas_income_delete_trigger 
        AFTER DELETE ON kas_masuk 
        FOR EACH ROW BEGIN  

        INSERT INTO saldo_history(kode_transaksi, type,jumlah,saldo_akhir,keterangan,created_at)  
        VALUES(old.kode_transaksi,"INCOME_CANCEL",old.jumlah,(select saldo from saldo limit 1)-old.jumlah,null,SYSDATE());
        UPDATE `saldo` SET `saldo`=(select saldo from saldo limit 1)-old.jumlah WHERE id =1;

        END
        ');


        DB::unprepared('
        CREATE TRIGGER kas_piutang_delete_trigger 
        AFTER DELETE ON piutangs
        FOR EACH ROW BEGIN  

        INSERT INTO saldo_history(kode_transaksi, type,jumlah,saldo_akhir,keterangan,created_at)  
        VALUES(old.kode_transaksi,"PIUTANG_CANCEL",old.jumlah,(select saldo from saldo limit 1)-old.jumlah,null,SYSDATE());
        UPDATE `saldo` SET `saldo`=(select saldo from saldo limit 1)+old.jumlah WHERE id =1;

        END
        ');

        DB::unprepared('
        CREATE TRIGGER hutang_delete_trigger 
        AFTER DELETE ON hutangs 
        FOR EACH ROW BEGIN  

        INSERT INTO saldo_history(kode_transaksi, type,jumlah,saldo_akhir,keterangan,created_at)  
        VALUES(old.kode_transaksi,"HUTANG_CANCEL",old.jumlah,(select saldo from saldo limit 1)-old.jumlah,null,SYSDATE());
        UPDATE `saldo` SET `saldo`=(select saldo from saldo limit 1)-old.jumlah WHERE id =1;

        END
        ');

        DB::unprepared('
        CREATE TRIGGER kas_outcome_delete_trigger 
        AFTER DELETE ON kas_keluar
        FOR EACH ROW BEGIN  

        INSERT INTO saldo_history(kode_transaksi, type,jumlah,saldo_akhir,keterangan,created_at)  
        VALUES(old.kode_transaksi,"OUTCOME_CANCEL",old.jumlah,(select saldo from saldo limit 1)+old.jumlah,null,SYSDATE());
        UPDATE `saldo` SET `saldo`=(select saldo from saldo limit 1)-old.jumlah WHERE id =1;

        END
        ');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `kas_masuk_history`');
        DB::unprepared('DROP TRIGGER `kas_keluar_history`');
        DB::unprepared('DROP TRIGGER `kas_income_delete_trigger`');
        DB::unprepared('DROP TRIGGER `kas_outcome_delete_trigger`');
    }
}
