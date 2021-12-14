<?php

namespace App\Http\Services;

use Illuminate\Http\Request;
use DOMAttr;

class CreateXmlService {

  public function createXML($data, $filename) {

    try {

      // Create the xml document
      $xmlDoc = new \DOMDocument();
      $xmlDoc->encoding = 'utf-8';
      $xmlDoc->xmlVersion = '1.0';
      $xmlDoc->standalone = 'no';
      $xmlDoc->formatOutput = true;

      // Create root.
      $root = $xmlDoc->createElement('invoice:request');
      $root->setAttributeNode(new DOMAttr('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance'));
      // Create invoice processing.
      $invoice_processing = $xmlDoc->createElement('invoice:processing');
      $invoice_transport = $xmlDoc->createElement('invoice:transport');
      // Set attribute node invoice:transport and invoice:via
      $invoice_transport->setAttributeNode(new DOMAttr('from', 7601001302112));
      $invoice_transport->setAttributeNode(new DOMAttr('to', 7634567890000));
      $invoice_via = $xmlDoc->createElement('invoice:via');
      $invoice_via->setAttributeNode(new DOMAttr('via', 2000012345678));
      $invoice_via->setAttributeNode(new DOMAttr('sequence_id', 1));
      // save child invoice:via which is in invoice:transport THEN invoice:transport which is in invoice:processing
      $invoice_transport->appendChild($invoice_via);
      $invoice_processing->appendChild($invoice_transport);
      // Save invoice processing in $root
      $root->appendChild($invoice_processing);


      /* --- Create the invoice payload --- */

      // Create invoice:payload
      $invoice_payload = $xmlDoc->createElement('invoice:payload');
      // Set attribute node invoice:payload
      $invoice_payload->setAttributeNode(new DOMAttr('type', 'invoice'));
      $invoice_payload->setAttributeNode(new DOMAttr('copy', 0));
      $invoice_payload->setAttributeNode(new DOMAttr('storno', 0));
      // Create invoice:invoice
      $invoice_invoice = $xmlDoc->createElement('invoice:invoice');
      // Set attribute node invoice:invoice
      $invoice_invoice->setAttributeNode(new DOMAttr('request_timestamp', 1619688895));
      $invoice_invoice->setAttributeNode(new DOMAttr('request_date', '2021-04-25T00:00:00'));
      $invoice_invoice->setAttributeNode(new DOMAttr('request_id', 2936699385));
      // save child invoice:invoice which is in invoice:playload
      $invoice_payload->appendChild($invoice_invoice);


      /* --- Create the invoice payload --- */

      // Create invoice:body which is in invoice:payload
      $invoice_body = $xmlDoc->createElement('invoice:body');
      // Set attribute node invoice:invoice
      $invoice_body->setAttributeNode(new DOMAttr('role', 'hospital'));
      $invoice_body->setAttributeNode(new DOMAttr('place', 'hospital'));


      /* --- Create the invoice prolog --- */

      // Create invoice:prolog which is in invoice:payload
      $invoice_prolog = $xmlDoc->createElement('invoice:prolog');
      $invoice_package = $xmlDoc->createElement('invoice:package');

      // Set attribute node invoice:package
      $invoice_package->setAttributeNode(new DOMAttr('name', 'GeneralInvoiceRequestTest'));
      $invoice_package->setAttributeNode(new DOMAttr('copyright', 'suva 2000-21'));
      $invoice_package->setAttributeNode(new DOMAttr('version', '100021'));

      // Create invoice:depends_on which is in invoice:generator
      $invoice_generator = $xmlDoc->createElement('invoice:generator');
      $invoice_depends_on = $xmlDoc->createElement('invoice:depends_on');

      // Set attribute node invoice:generator and invoice:depends_on
      $invoice_generator->setAttributeNode(new domAttr('name','GeneralInvoiceRequestManager 4.50.019'));
      $invoice_generator->setAttributeNode(new domAttr('copyright','suva 2000-21'));
      $invoice_generator->setAttributeNode(new domAttr('version',450));
      $invoice_depends_on->setAttributeNode(new domAttr('name','drgValidator ATL Module'));
      $invoice_depends_on->setAttributeNode(new domAttr('copyright','Suva'));
      $invoice_depends_on->setAttributeNode(new domAttr('version',100));
      $invoice_depends_on->setAttributeNode(new domAttr('id',1007090101));

      // save child invoice:depends_on which is in invoice:generator
      $invoice_generator->appendChild($invoice_depends_on);
      $invoice_prolog->appendChild($invoice_generator);

      // save child invoice:package which is in invoice:prolog
      $invoice_prolog->appendChild($invoice_package);
      $invoice_body->appendChild($invoice_prolog);


      /* --- Create invoice invoice:remark. --- */

      // Create invoice:remark with texte Between tag/node(balise) wich is in invoice:payload
      $texte_remark= 'Lorem ipsum per nostra mi fune torectum mikonstra.diloru si limus mer fin per od per nostra mi fune torectum mi konstradiloru si limus mer fin itorectum mi konstradiloruko.';
      $invoice_remark = $xmlDoc->createElement('invoice:remark', $texte_remark);
      // save child invoice:remark which is in invoice:payload
      $invoice_body->appendChild($invoice_remark);


      /* --- Create invoice invoice:invoice_tiers_payant. --- */

      // Create invoice:biller which is in invoice:tiers_payant
      $invoice_tiers_payant = $xmlDoc->createElement('invoice:invoice_tiers_payant');
      $invoice_biller = $xmlDoc->createElement('invoice:biller');

      // Set attribute node invoice:biller which is in invoice:tiers_payant
      $invoice_biller->setAttributeNode(new domAttr('ean_party',2011234567890));
      $invoice_biller->setAttributeNode(new domAttr('zsr','H121111'));
      $invoice_biller->setAttributeNode(new domAttr('uid_number','CHE108791452'));

      // Create invoice:company which is in invoice:biller
      $invoice_company = $xmlDoc->createElement('invoice:company');
      $invoice_company_name = $xmlDoc->createElement('invoice:companyname', 'Biller AG');
      $invoice_company->appendChild($invoice_company_name);
      $invoice_department = $xmlDoc->createElement('invoice:department', 'Abteilung Inkasso');
      $invoice_company->appendChild($invoice_department);
      $invoice_postal = $xmlDoc->createElement('invoice:postal');
      $invoice_street = $xmlDoc->createElement('invoice:street', 'Billerweg 128');
      $invoice_zip = $xmlDoc->createElement('invoice:zip', 4414);
      $invoice_city = $xmlDoc->createElement('invoice:city', 'Frenkendorf');
      $invoice_postal->appendChild($invoice_street);
      $invoice_postal->appendChild($invoice_zip);
      $invoice_postal->appendChild($invoice_city);
      $invoice_company->appendChild($invoice_postal);
      $invoice_biller->appendChild($invoice_company);

      // Create invoice:telecom and online which is in invoice:company
      $invoice_telecom = $xmlDoc->createElement('invoice:telecom');
      $invoice_phone = $xmlDoc->createElement('invoice:phone','061 956 99 00');
      $invoice_fax = $xmlDoc->createElement('invoice:fax','061 956 99 10');
      $invoice_telecom->appendChild($invoice_phone);
      $invoice_telecom->appendChild($invoice_fax);
      $invoice_company->appendChild($invoice_telecom);
      $invoice_online = $xmlDoc->createElement('invoice:online');
      $invoice_email = $xmlDoc->createElement('invoice:email','info@biller.ch');
      $invoice_online->appendChild($invoice_email);
      $invoice_company->appendChild($invoice_online);

      // save child invoice:biller wich is in invoice:invoice_tiers_payant
      $invoice_tiers_payant->appendChild($invoice_biller);
      $invoice_body->appendChild($invoice_tiers_payant);


      /* --- Create invoice invoice:debitor. --- */

      // Create invoice:debitor which is in invoice:tiers_payant
      $invoice_debitor = $xmlDoc->createElement('invoice:debitor');

      // Set attribute node invoice:biller which is in invoice:tiers_payant
      $invoice_debitor->setAttributeNode(new domAttr('ean_party',7634567890000));

      // Create invoice:company which is in invoice:debitor
      $invoice_company = $xmlDoc->createElement('invoice:company');
      $invoice_company_name = $xmlDoc->createElement('invoice:companyname', 'Rehaklinik zur Genesung');
      $invoice_company->appendChild($invoice_company_name);
      $invoice_postal = $xmlDoc->createElement('invoice:postal');
      $invoice_street = $xmlDoc->createElement('invoice:street', 'Kassengraben 222');
      $invoice_zip = $xmlDoc->createElement('invoice:zip', 4000);
      $invoice_city = $xmlDoc->createElement('invoice:city', 'Basel');
      $invoice_postal->appendChild($invoice_street);
      $invoice_postal->appendChild($invoice_zip);
      $invoice_postal->appendChild($invoice_city);
      $invoice_company->appendChild($invoice_postal);
      $invoice_debitor->appendChild($invoice_company);

      // save child invoice:depends_on which is in invoice:generator
      $invoice_tiers_payant->appendChild($invoice_debitor);


      /* --- Create invoice invoice:provider. --- */

      // Create invoice:debitor which is in invoice:tiers_payant
      $invoice_provider = $xmlDoc->createElement('invoice:provider');

      // Set attribute node invoice:biller which is in invoice:tiers_payant
      $invoice_provider->setAttributeNode(new domAttr('ean_party',7634567890111));
      $invoice_provider->setAttributeNode(new domAttr('zsr','P123456'));

      // Create invoice:company which is in invoice:provider
      $invoice_company = $xmlDoc->createElement('invoice:company');
      $invoice_company_name = $xmlDoc->createElement('invoice:companyname', 'Rehaklinik zur Genesung');
      $invoice_company->appendChild($invoice_company_name);
      $invoice_postal = $xmlDoc->createElement('invoice:postal');
      $invoice_street = $xmlDoc->createElement('invoice:street', 'Spitalgasse 17b5');
      $invoice_zip->setAttributeNode(new domAttr('statecode','BS'));
      $invoice_zip = $xmlDoc->createElement('invoice:zip', 4000);
      $invoice_city = $xmlDoc->createElement('invoice:city', 'Basel');
      $invoice_postal->appendChild($invoice_street);
      $invoice_postal->appendChild($invoice_zip);
      $invoice_postal->appendChild($invoice_city);
      $invoice_company->appendChild($invoice_postal);
      $invoice_provider->appendChild($invoice_company);

      // save child invoice:provider which is in invoice:tiers_payant
      $invoice_tiers_payant->appendChild($invoice_provider);

      // Create invoice:telecom which is in invoice:company
      $invoice_telecom = $xmlDoc->createElement('invoice:telecom');
      $invoice_phone = $xmlDoc->createElement('invoice:phone','061 956 99 00');
      $invoice_fax = $xmlDoc->createElement('invoice:fax','061 956 99 10');
      $invoice_telecom->appendChild($invoice_phone);
      $invoice_telecom->appendChild($invoice_fax);
      $invoice_company->appendChild($invoice_telecom);


      /* --- Create invoice:insurance. --- */

      // Create invoice:insurance which is in invoice:tiers_payant
      $invoice_insurance = $xmlDoc->createElement('invoice:insurance');

      // Set attribute node invoice:insurance which is in invoice:tiers_payant
      $invoice_insurance->setAttributeNode(new domAttr('ean_party',7634567890000));

      // Create invoice:company which is in invoice:provider
      $invoice_company = $xmlDoc->createElement('invoice:company');
      $invoice_company_name = $xmlDoc->createElement('invoice:companyname', 'Krankenkasse AG');
      $invoice_departement = $xmlDoc->createElement('invoice:departement', 'Sektion Basel');
      $invoice_company->appendChild($invoice_company_name);
      $invoice_company->appendChild($invoice_departement);
      $invoice_postal = $xmlDoc->createElement('invoice:postal');
      $invoice_street = $xmlDoc->createElement('invoice:street', 'Kassengraben 222');
      $invoice_zip->setAttributeNode(new domAttr('statecode','BS'));
      $invoice_zip = $xmlDoc->createElement('invoice:zip', 4000);
      $invoice_city = $xmlDoc->createElement('invoice:city', 'Basel');
      $invoice_postal->appendChild($invoice_street);
      $invoice_postal->appendChild($invoice_zip);
      $invoice_postal->appendChild($invoice_city);
      $invoice_company->appendChild($invoice_postal);
      $invoice_insurance->appendChild($invoice_company);

      // save child invoice:insurance which is in invoice:tiers_payant
      $invoice_tiers_payant->appendChild($invoice_insurance);

      /* --- Create invoice:patient. --- */

      // Create invoice:patient and invoice:personne which is in invoice:tiers_payant
      $invoice_patient = $xmlDoc->createElement('invoice:patient');
      $invoice_person = $xmlDoc->createElement('invoice:person');

      // Set attribute node patient and invoice:personne which is in invoice:tiers_payant
      $invoice_patient->setAttributeNode(new domAttr('gender','female'));
      $invoice_patient->setAttributeNode(new domAttr('birthdate','2004-02-02T00:00:00'));
      $invoice_patient->setAttributeNode(new domAttr('ssn',7561234567890));
      $invoice_person->setAttributeNode(new domAttr('salutation','Frau'));
      $invoice_patient->appendChild($invoice_person);

      // Create invoice:patient and invoice:personne which is in invoice:tiers_payant
      $invoice_family_name = $xmlDoc->createElement('invoice:familyname','Muster');
      $invoice_give_name = $xmlDoc->createElement('invoice:givenname','Petra');
      $invoice_person->appendChild($invoice_family_name);
      $invoice_person->appendChild($invoice_give_name);

      // Create invoice:postal  which is in invoice:person
      $invoice_postal = $xmlDoc->createElement('invoice:postal');
      $invoice_street = $xmlDoc->createElement('invoice:street','Musterstrasse 5');
      $invoice_zip = $xmlDoc->createElement('invoice:zip',7304);
      $invoice_city = $xmlDoc->createElement('invoice:city','Maienfeld');
      $invoice_person->appendChild($invoice_street);
      $invoice_person->appendChild($invoice_zip);
      $invoice_person->appendChild($invoice_city);
      $invoice_patient->appendChild($invoice_postal);

      // Create invoice:card  which is in invoice:patient
      $invoice_card = $xmlDoc->createElement('invoice:card');
      $invoice_card->setAttributeNode(new domAttr('card_id',12345678901234567890));
      $invoice_card->setAttributeNode(new domAttr('expiry_date','2021-08-01T00:00:00'));
      $invoice_patient->appendChild($invoice_card);
      $invoice_tiers_payant->appendChild($invoice_patient);


      /* --- Create invoice:guarantor. --- */

      // Create invoice:guarantor and invoice:personne which is in invoice:tiers_payant
      $invoice_guarantor = $xmlDoc->createElement('invoice:guarantor');
      $invoice_person = $xmlDoc->createElement('invoice:person');

      // Set attribute node patient and invoice:personne which is in invoice:tiers_payant
      $invoice_person->setAttributeNode(new domAttr('salutation','Frau'));
      $invoice_guarantor->appendChild($invoice_person);

      // Create invoice:patient and invoice:personne which is in invoice:tiers_payant
      $invoice_family_name = $xmlDoc->createElement('invoice:familyname','Muster');
      $invoice_give_name = $xmlDoc->createElement('invoice:givenname','Petra');
      $invoice_person->appendChild($invoice_family_name);
      $invoice_person->appendChild($invoice_give_name);

      // Create invoice:postal  which is in invoice:person
      $invoice_postal = $xmlDoc->createElement('invoice:postal');
      $invoice_street = $xmlDoc->createElement('invoice:street','Musterstrasse 5');
      $invoice_zip = $xmlDoc->createElement('invoice:zip',7304);
      $invoice_city = $xmlDoc->createElement('invoice:city','Maienfeld');
      $invoice_person->appendChild($invoice_street);
      $invoice_person->appendChild($invoice_zip);
      $invoice_person->appendChild($invoice_city);
      $invoice_guarantor->appendChild($invoice_postal);
      $invoice_tiers_payant->appendChild($invoice_guarantor);


      /* --- Create invoice:balance. --- */

      // Create invoice:balance/vat/vat_rate which is in invoice:tiers_payant
      $invoice_balance = $xmlDoc->createElement('invoice:balance');
      $invoice_vat = $xmlDoc->createElement('invoice:vat');
      $invoice_vat_rate = $xmlDoc->createElement('invoice:vat_rate');


      // Set attribute node balance/vat/vat_rate which is in invoice:tiers_payant
      $invoice_balance->setAttributeNode(new domAttr('currency','CHF'));
      $invoice_balance->setAttributeNode(new domAttr('amount',53199.30));
      $invoice_balance->setAttributeNode(new domAttr('amount_obligations',53199.30));
      $invoice_balance->setAttributeNode(new domAttr('amount_due',53199.30));
      $invoice_vat->setAttributeNode(new domAttr('vat',0.00));
      $invoice_vat->setAttributeNode(new domAttr('vat_number','CHE108791452'));
      $invoice_vat_rate->setAttributeNode(new domAttr('vat_rate',0.00));
      $invoice_vat_rate->setAttributeNode(new domAttr('vat_rate',0));
      $invoice_vat_rate->setAttributeNode(new domAttr('vat_rate',53199.30));

      // save child invoice:balance/vat/vat_rate which is in invoice:tiers_payant
      $invoice_vat->appendChild($invoice_vat_rate);
      $invoice_balance->appendChild($invoice_vat);
      $invoice_tiers_payant->appendChild($invoice_balance);


      /* --- Create invoice:esrQR. --- */

      // Create invoice:esrQR
      $invoice_esrQR = $xmlDoc->createElement('invoice:esrQR');

      // Set attribute node esrQR
      $invoice_esrQR->setAttributeNode(new domAttr('iban','CH0930769016110591261'));
      $invoice_esrQR->setAttributeNode(new domAttr('type','esrQR'));
      $invoice_esrQR->setAttributeNode(new domAttr('reference_number',210000000003139471430009017));
      $invoice_esrQR->setAttributeNode(new domAttr('customer_note','This is an individuell customer note separated by several lines'));

      // save child invoice:esrQR
      $invoice_body->appendChild($invoice_esrQR);

      // Create invoice:bank which is in invoice:esrQR
      $invoice_bank = $xmlDoc->createElement('invoice:bank');
      $invoice_company = $xmlDoc->createElement('invoice:company');
      $invoice_companyname = $xmlDoc->createElement('invoice:companyname','Bank AG');
      $invoice_department = $xmlDoc->createElement('department','Abteilung VESR');

      // save child invoice:bank
      $invoice_company->appendChild($invoice_companyname);
      $invoice_company->appendChild($invoice_department);
      $invoice_bank->appendChild($invoice_company);
      $invoice_esrQR->appendChild($invoice_bank);


      /* --- Create invoice:creditor. --- */

      // Create invoice:creditor which is in invoice:esrQR
      $invoice_creditor = $xmlDoc->createElement('invoice:creditor');
      $invoice_company = $xmlDoc->createElement('invoice:company');
      $invoice_company_name = $xmlDoc->createElement('invoice:companyname','CreditorenAllianz beider Basel');
      $invoice_department = $xmlDoc->createElement('invoice:department','GmbH and Co KGl');
      $invoice_company->appendChild($invoice_company_name);
      $invoice_company->appendChild($invoice_department);

      $invoice_postal = $xmlDoc->createElement('invoice:postal');
      $invoice_street = $xmlDoc->createElement('invoice:street', 'Billerweg 128');
      $invoice_zip->setAttributeNode(new domAttr('countrycode','CH'));
      $invoice_zip = $xmlDoc->createElement('invoice:zip', 4414);
      $invoice_city = $xmlDoc->createElement('invoice:city', 'Frenkendorf');

      // save child invoice:postal which is in invoice:company
      $invoice_postal->appendChild($invoice_street);
      $invoice_postal->appendChild($invoice_zip);
      $invoice_postal->appendChild($invoice_city);
      $invoice_company->appendChild($invoice_postal);
      $invoice_creditor->appendChild($invoice_company);

      // save child invoice:creditor which is in invoice:esrQR
      $invoice_esrQR->appendChild($invoice_creditor);


      /* --- Create invoice:kvg. --- */

      // Create invoice:kvg
      $invoice_kvg = $xmlDoc->createElement('invoice:kvg');

      // Set attribute node esrQR
      $invoice_kvg->setAttributeNode(new domAttr('case_id','123456-6789'));
      $invoice_kvg->setAttributeNode(new domAttr('case_date','2021-04-25T00:00:00'));
      $invoice_kvg->setAttributeNode(new domAttr('reference_number','123.45.678-012'));

      $invoice_body->appendChild($invoice_kvg);


      /* --- Create invoice:treatment. --- */

      // Create invoice:treatment
      $invoice_treatment = $xmlDoc->createElement('invoice:treatment');

      // Set attribute node treatment
      $invoice_treatment->setAttributeNode(new domAttr('date_begin','2021-02-10T09:00:00'));
      $invoice_treatment->setAttributeNode(new domAttr('date_end','2021-04-23T09:00:00'));
      $invoice_treatment->setAttributeNode(new domAttr('canton','BS'));
      $invoice_treatment->setAttributeNode(new domAttr('reason','disease'));
      $invoice_treatment->setAttributeNode(new domAttr('apid','stRehaID_1456'));
      $invoice_treatment->setAttributeNode(new domAttr('acid','Reha005.4'));

      // save child invoice:treatment which is in invoice:body
      $invoice_body->appendChild($invoice_treatment);


      /* --- Create invoice:diagnosis. --- */

      // Create invoice:diagnosis
      $invoice_diagnosis = $xmlDoc->createElement('invoice:diagnosis');

      // Set attribute node diagnosis
      $invoice_diagnosis->setAttributeNode(new domAttr('type','ICD'));
      $invoice_diagnosis->setAttributeNode(new domAttr('code','M00.10'));
      $invoice_diagnosis = $xmlDoc->createElement('invoice:diagnosis','Arthritis und Polyarthritis durch Pneumokokken: Mehrere Lokalisationen');

      // save child invoice:diagnosis which is in invoice:treatment
      $invoice_treatment->appendChild($invoice_diagnosis);


      /* --- Create invoice:xtra_hospital. --- */

      // Create invoice:xtra_hospital
      $invoice_xtra_hospital = $xmlDoc->createElement('invoice:xtra_hospital');
      $invoice_stationary = $xmlDoc->createElement('invoice:stationary');

      // Set attribute node xtra_hospital
      $invoice_stationary->setAttributeNode(new domAttr('section_major','M00'));
      $invoice_stationary->setAttributeNode(new domAttr('hospitalization_type','regular'));
      $invoice_stationary->setAttributeNode(new domAttr('hospitalization_mode','noncantonal_indicated'));
      $invoice_stationary->setAttributeNode(new domAttr('class','general'));
      $invoice_stationary->setAttributeNode(new domAttr('treatment_days','P73D'));
      $invoice_stationary->setAttributeNode(new domAttr('hospitalization_date','2021-02-10T09:00:00'));
      $invoice_stationary->setAttributeNode(new domAttr('has_expense_loading',0));

      $invoice_admission_type = $xmlDoc->createElement('invoice:admission_type');
      $invoice_discharge_type = $xmlDoc->createElement('invoice:discharge_type');
      $invoice_provider_type = $xmlDoc->createElement('invoice:provider_type');
      $invoice_bfs_residence_before_admission = $xmlDoc->createElement('invoice:_bfs_residence_before_admission');
      $invoice_bfs_admission_type = $xmlDoc->createElement('invoice:_bfs_residence_before_admission');
      $invoice_bfs_decision_for_discharge = $xmlDoc->createElement('invoice:bfs_decision_for_discharge');
      $invoice_bfs_residence_after_dischargel = $xmlDoc->createElement('invoice:bfs_residence_after_dischargel');

      $invoice_admission_type->setAttributeNode(new domAttr('number',0));
      $invoice_admission_type->setAttributeNode(new domAttr('name','normal'));

      $invoice_discharge_type->setAttributeNode(new domAttr('number',0));
      $invoice_discharge_type->setAttributeNode(new domAttr('name','normal'));

      $invoice_provider_type->setAttributeNode(new domAttr('number',0));
      $invoice_provider_type->setAttributeNode(new domAttr('name','normal'));

      $invoice_bfs_residence_before_admission->setAttributeNode(new domAttr('code',1));
      $invoice_bfs_residence_before_admission->setAttributeNode(new domAttr('code','M00'));

      $invoice_bfs_admission_type->setAttributeNode(new domAttr('code',3));
      $invoice_bfs_admission_type->setAttributeNode(new domAttr('name','Zuhause'));

      $invoice_bfs_decision_for_discharge->setAttributeNode(new domAttr('code',1));
      $invoice_bfs_decision_for_discharge->setAttributeNode(new domAttr('name','auf Initiative des Behandelnden'));

      $invoice_bfs_residence_after_dischargel->setAttributeNode(new domAttr('code',1));
      $invoice_bfs_residence_after_dischargel->setAttributeNode(new domAttr('name','Zuhause'));

      // save child invoice:treatment which is in invoice:body
      $invoice_stationary->appendChild($invoice_admission_type);
      $invoice_stationary->appendChild($invoice_discharge_type);
      $invoice_stationary->appendChild($invoice_provider_type);
      $invoice_stationary->appendChild($invoice_bfs_residence_before_admission);
      $invoice_stationary->appendChild($invoice_bfs_admission_type);
      $invoice_stationary->appendChild($invoice_bfs_decision_for_discharge);
      $invoice_stationary->appendChild($invoice_bfs_residence_after_dischargel);

      $invoice_xtra_hospital->appendChild($invoice_stationary);
      $invoice_treatment->appendChild($invoice_xtra_hospital);


      /* --- Create invoice:treatment. --- */

      // Create invoice:treatment
      $invoice_treatment = $xmlDoc->createElement('invoice:treatment');

      // Set attribute node treatment
      $invoice_treatment->setAttributeNode(new domAttr('date_begin','2021-02-10T09:00:00'));
      $invoice_treatment->setAttributeNode(new domAttr('date_end','2021-04-23T09:00:00'));
      $invoice_treatment->setAttributeNode(new domAttr('canton','BS'));
      $invoice_treatment->setAttributeNode(new domAttr('reason','disease'));
      $invoice_treatment->setAttributeNode(new domAttr('apid','stRehaID_1456'));
      $invoice_treatment->setAttributeNode(new domAttr('acid','Reha005.4'));

      // save child invoice:treatment which is in invoice:body
      $invoice_body->appendChild($invoice_treatment);


      /* --- Create invoice:diagnosis. --- */

      // Create invoice:diagnosis
      $invoice_diagnosis = $xmlDoc->createElement('invoice:diagnosis');

      // Set attribute node diagnosis
      $invoice_diagnosis->setAttributeNode(new domAttr('type','ICD'));
      $invoice_diagnosis->setAttributeNode(new domAttr('code','M00.10'));
      $invoice_diagnosis = $xmlDoc->createElement('invoice:diagnosis','Arthritis und Polyarthritis durch Pneumokokken: Mehrere Lokalisationen');

      // save child invoice:diagnosis which is in invoice:treatment
      $invoice_treatment->appendChild($invoice_diagnosis);


      /* --- Create invoice:services. --- */

      // Create invoice:services
      $invoice_services = $xmlDoc->createElement('invoice:services');
      $invoice_service = $xmlDoc->createElement('invoice:service');

      // Set attribute node treatment
      $invoice_service->setAttributeNode(new domAttr('record_id',1));
      $invoice_service->setAttributeNode(new domAttr('tariff_type',020));
      $invoice_service->setAttributeNode(new domAttr('code','TR11A'));
      $invoice_service->setAttributeNode(new domAttr('session',1));
      $invoice_service->setAttributeNode(new domAttr('quantity',1));

      $invoice_service->setAttributeNode(new domAttr('date_begin','2021-02-10T00:00:00'));
      $invoice_service->setAttributeNode(new domAttr('date_end','2021-04-23T00:00:00'));
      $invoice_service->setAttributeNode(new domAttr('provider_id',7634567890111));
      $invoice_service->setAttributeNode(new domAttr('responsible_id',7634567890333));
      $invoice_service->setAttributeNode(new domAttr('unit','141.264'));
      $invoice_service->setAttributeNode(new domAttr('unit_factor',759));
      $invoice_service->setAttributeNode(new domAttr('external_factor','0.49'));
      $invoice_service->setAttributeNode(new domAttr('amount','52537.49'));
      $invoice_service->setAttributeNode(new domAttr('service_attributes',0));
      $invoice_service->setAttributeNode(new domAttr('obligation',1));
      $invoice_service->setAttributeNode(new domAttr('name','Rehabilitation für Kinder und Jugendliche, Alter kleiner 19 Jahre, mit komplizierender Diagnose'));
      $invoice_service->setAttributeNode(new domAttr('section_code','M00'));

      // save child invoice:treatment which is in invoice:body
      $invoice_services->appendChild($invoice_service);
      $invoice_body->appendChild($invoice_services);

      // Save save.
      $invoice_payload->appendChild($invoice_body);

      // Save payload.
      $root->appendChild($invoice_payload);


      /* --- End of processus --- */

      // Save XML to file.
      $xmlDoc->appendChild($root);
      $xmlDoc->save(public_path($filename));

      return $xmlDoc;

    } catch (\Exception $e) {
      throw($e);
    }

  }

}

?>