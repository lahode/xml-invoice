<?php

namespace App\Http\Services;

use Illuminate\Http\Request;
use App\Exceptions\ApiException;
use DomDocument;
use DomXPath;

class ReadXmlService {

  public function readXML($filename) {

    try {

      // Check if file exists.
      if (!file_exists(public_path($filename))) {
        throw new ApiException(
          "File not found",
          404
        );        
      }

      // Use Dom methode to get data
      $dom = new DomDocument("1.0", "ISO-8859-1");
      $dom->load(public_path($filename));
      $xpath = new DomXPath($dom);

      // Get attribut in payload
      $xmlPayload = $xpath->query('//invoice:payload');
      $payload = [
        'copie_facture' => $xmlPayload[0]->getAttribute('copy'),
      ];

      // Get INVOICE:INVOICE in playload
      $xmlInvoice = $xpath->query('//invoice:invoice');
      $invoice = [
        'identification' => $xmlInvoice[0]->getAttribute('request_timestamp'),
        'date' => $xmlInvoice[0]->getAttribute('request_date'),
      ];

      // Get body
      $xmlBody = $xpath->query('//invoice:body');
      $body = [
        'rôle' => $xmlBody[0]->getAttribute('role'),
        'localité' => $xmlBody[0]->getAttribute('place'),
      ];

      //Get biller
      $xmlBiller = $xpath->query('//invoice:biller');
      $xmlBillerCompanyName = $xpath->query('//invoice:biller/invoice:company/invoice:companyname');
      $xmlBillerDepartement = $xpath->query('//invoice:biller/invoice:company/invoice:department');
      $xmlBillerPostal = $xpath->query('//invoice:biller/invoice:company/invoice:postal');
      $xmlBillerTelecom = $xpath->query('//invoice:biller/invoice:company/invoice:telecom');
      $xmlBillerOnline = $xpath->query('//invoice:biller/invoice:company/invoice:online');
      $biller = [
        'gln' => $xmlBiller[0]->getAttribute('ean_party'),
        'rcc' => $xmlBiller[0]->getAttribute('zsr'),
        'companyname' => $xmlBillerCompanyName[0]->nodeValue,
        'departement' => $xmlBillerDepartement[0]->nodeValue,
        'address' => [
          'street' => $xmlBillerPostal[0]->childNodes[0]->nodeValue,
          'zip' => $xmlBillerPostal[0]->childNodes[1]->nodeValue,
          'city' => $xmlBillerPostal[0]->childNodes[2]->nodeValue
        ],
        'phones' => [
          'tel' => $xmlBillerTelecom[0]->childNodes[0]->nodeValue,
          'fax' => $xmlBillerTelecom[0]->childNodes[1]->nodeValue // use childNodes and nodeValue to get same name for data
        ],
        'email' => $xmlBillerOnline[0]->childNodes[0]->nodeValue
      ];

      // Get debitor
      $xmlDebitor = $xpath->query('//invoice:debitor');
      $xmlDebitorCompanyName = $xpath->query('//invoice:debitor/invoice:company/invoice:companyname');
      $xmlDebitorDepartement = $xpath->query('//invoice:debitor/invoice:company/invoice:department');
      $xmlDebitorPostal = $xpath->query('//invoice:debitor/invoice:company/invoice:postal');
      $debitor = [
        'gln' => $xmlDebitor[0]->getAttribute('ean_party'),
        'companyname' => $xmlDebitorCompanyName[0]->nodeValue,
        'departement' => $xmlDebitorDepartement[0]->nodeValue,
        'address' => [
          'street' => $xmlDebitorPostal[0]->childNodes[0]->nodeValue,
          'zip' => $xmlDebitorPostal[0]->childNodes[1]->nodeValue,// use childNodes and nodeValue to get same name for data
          'city' => $xmlDebitorPostal[0]->childNodes[2]->nodeValue
        ]
      ];

      // Get provider
      $xmlProvider = $xpath->query('//invoice:provider');
      $xmlProviderCompanyName = $xpath->query('//invoice:provider/invoice:company/invoice:companyname');
      $xmlProviderPostal = $xpath->query('//invoice:provider/invoice:company/invoice:postal');
      $xmlProviderTelecom = $xpath->query('//invoice:provider/invoice:company/invoice:telecom');
      $provider = [
        'gln' => $xmlProvider[0]->getAttribute('ean_party'),
        'rcc' => $xmlProvider[0]->getAttribute('zsr'),
        'companyname' => $xmlProviderCompanyName[0]->nodeValue,
        'departement' => $xmlProviderPostal[0]->nodeValue,
        'address' => [
          'street' => $xmlProviderPostal[0]->childNodes[0]->nodeValue,
          'zip' => $xmlProviderPostal[0]->childNodes[1]->nodeValue,// use childNodes and nodeValue to get same name for data
          'city' => $xmlProviderPostal[0]->childNodes[2]->nodeValue
        ],
        'phones' => [
          'tel' => $xmlProviderTelecom[0]->childNodes[0]->nodeValue,
          'fax' => $xmlProviderTelecom[0]->childNodes[1]->nodeValue
        ]
      ];

      //Get patient
      $xmlPatient = $xpath->query('//invoice:patient');
      $xmlPerson = $xpath->query('//invoice:patient/invoice:person');
      $xmlPatientFamilyname = $xpath->query('//invoice:patient/invoice:person/invoice:familyname');
      $xmlPatientGivenname = $xpath->query('//invoice:patient/invoice:person/invoice:givenname');
      $xmlPatientPostal = $xpath->query('//invoice:patient/invoice:person/invoice:postal');
      $patient = [
        'gender' => $xmlPatient[0]->getAttribute('gender'),
        'birthdate' => $xmlPatient[0]->getAttribute('birthdate'),
        'person' => $xmlPerson[0]->getAttribute('salutation'),
        'familyname' => $xmlPatientFamilyname[0]->nodeValue,
        'givenname' => $xmlPatientGivenname[0]->nodeValue,
        'postal' => [
          'street' => $xmlPatientPostal[0]->childNodes[0]->nodeValue,
          'zip' => $xmlPatientPostal[0]->childNodes[1]->nodeValue,// use childNodes and nodeValue to get same name for data
          'city' => $xmlPatientPostal[0]->childNodes[2]->nodeValue
        ]
      ];

      // Get balance
      $xmlBalance = $xpath->query('//invoice:balance');
      $xmlBalanceVat = $xpath->query('//invoice:balance/invoice:vat/invoice:vat_rate');
      $balance = [
        // invoice:balance
        'currency' => $xmlBalance[0]->getAttribute('currency'),
        'amount' => $xmlBalance[0]->getAttribute('amount'),
        'amount_obligations' => $xmlBalance[0]->getAttribute('amount_obligations'),
        'amount_due' => $xmlBalance[0]->getAttribute('amount_due'),
        // vat in balance/vat
        'vat' => [
          'vat' => $xmlBalance[0]->childNodes[0]->getAttribute('vat'),
          'vat_number' => $xmlBalance[0]->childNodes[0]->getAttribute('vat_number'),
          'vat' => $xmlBalance[0]->childNodes[0]->getAttribute('vat'),
        ],
        // vat_rate in balance/vat/vat_rate
        'vat_rate' => [
          'amount' => $xmlBalanceVat[0]->getAttribute('amount'),
          'vat_rate' => $xmlBalanceVat[0]->getAttribute('vat_rate'),
          'vat' => $xmlBalanceVat[0]->getAttribute('vat'),
        ]
      ];

      // Get esrQR
      $xmlEsrQR = $xpath->query('//invoice:esrQR');
      $esrQR = [
        'iban' => $xmlEsrQR[0]->getAttribute('iban'),
        'type' => $xmlEsrQR[0]->getAttribute('type'),
        'reference_number' => $xmlEsrQR[0]->getAttribute('reference_number'),
        'customer_note' => $xmlEsrQR[0]->getAttribute('customer_note'),
      ];

      // Get kvg
      $xmlKvg = $xpath->query('//invoice:kvg');
      $xmlKvg = $xpath->query('//invoice:kvg');
      $xmlKvg = $xpath->query('//invoice:kvg');
      $kgv = [
        'case N°' => $xmlKvg[0]->getAttribute('case_id'),
        'date' => $xmlKvg[0]->getAttribute('case_date'),
        'assured N°' => $xmlKvg[0]->getAttribute('insured_id'),
      ];

      // Get treatment
      $xmlTreatment = $xpath->query('//invoice:treatment');
      $xmlTreatmentDiagnosis = $xpath->query('//invoice:treatment/invoice:diagnosis');
      $xmlTreatmentStationary = $xpath->query('//invoice:treatment/invoice:xtra_hospital/invoice:stationary');
      $treatment = [
        'debut_traitement' => $xmlTreatment[0]->getAttribute('date_begin'),
        'fin_traitement' => $xmlTreatment[0]->getAttribute('date_end'),
        'motif_traitement' => $xmlTreatment[0]->getAttribute('reason'),
        'apid' => $xmlTreatment[0]->getAttribute('apid'),
        'acid' => $xmlTreatment[0]->getAttribute('acid'),
        //diagnosis
        'type_diagnosis' => $xmlTreatmentDiagnosis[0]->getAttribute('type'),
        'code_diagnosis' => $xmlTreatmentDiagnosis[0]->getAttribute('code'),
        // stasionary in treatment/stationary
        'section_major' => $xmlTreatmentStationary[0]->getAttribute('section_major'),
        'hospitalization_type' => $xmlTreatmentStationary[0]->getAttribute('hospitalization_type'),
        'hospitalization_mode' => $xmlTreatmentStationary[0]->getAttribute('hospitalization_mode'),
        'hospitalization_date' => $xmlTreatmentStationary[0]->getAttribute('hospitalization_date'),
        // admission type in treatment/stationary
        'admission_type' => [
          'number' => $xmlTreatmentStationary[0]->childNodes[0]->getAttribute('number'),
          'name' => $xmlTreatmentStationary[0]->childNodes[0]->getAttribute('name')
        ],
        // discharge type in treatment/stationary
        'discharge_type' => [
          'number' => $xmlTreatmentStationary[0]->childNodes[1]->getAttribute('number'),
          'name' => $xmlTreatmentStationary[0]->childNodes[1]->getAttribute('name')// use childNodes and getAttribute to get same name for data
        ],
        // provider type in treatment/stationary
        'provider_type' => [
          'number' => $xmlTreatmentStationary[0]->childNodes[2]->getAttribute('number'),// use childNodes and getAttribute to get same name for data
          'name' => $xmlTreatmentStationary[0]->childNodes[2]->getAttribute('name')
        ],
        // bfs_residence_before_admission type in treatment/stationary
        'bfs_residence_before_admission' => [
          'code' => $xmlTreatmentStationary[0]->childNodes[3]->getAttribute('code'),// use childNodes and getAttribute to get same name for data
          'name' => $xmlTreatmentStationary[0]->childNodes[3]->getAttribute('name')
        ],
        // bfs_admission_type in treatment/stationary
        'bfs_admission_type' => [
          'code' => $xmlTreatmentStationary[0]->childNodes[4]->getAttribute('code'),// use childNodes and getAttribute to get same name for data
          'name' => $xmlTreatmentStationary[0]->childNodes[4]->getAttribute('name')
        ],
        // bfs_decision_for_discharge in treatment/stationary
        'bfs_decision_for_discharge' => [
          'code' => $xmlTreatmentStationary[0]->childNodes[5]->getAttribute('code'),// use childNodes and getAttribute to get same name for data
          'name' => $xmlTreatmentStationary[0]->childNodes[5]->getAttribute('name')
        ],
        // bfs_residence_after_discharge in treatment/stationary
        'bfs_residence_after_discharge' => [
          'code' => $xmlTreatmentStationary[0]->childNodes[6]->getAttribute('code'),// use childNodes and getAttribute to get same name for data
          'name' => $xmlTreatmentStationary[0]->childNodes[6]->getAttribute('name')
        ]
      ];

      // Get services
      $xmlServices = $xpath->query('//invoice:services/invoice:service');
      $services = [
        // ligne 1 -> bas de page
        'provider_id' => $xmlServices[0]->getAttribute('provider_id'),
        'responsible_id' => $xmlServices[0]->getAttribute('responsible_id'),
        'date_begin' => $xmlServices[0]->getAttribute('date_begin'),
        'tariff_type' => $xmlServices[0]->getAttribute('tariff_type'),
        'code' => $xmlServices[0]->getAttribute('code'),
        'session' => $xmlServices[0]->getAttribute('session'),
        'quantité' => $xmlServices[0]->getAttribute('quantity'),
        'Pt PM/Prix' => $xmlServices[0]->getAttribute('unit'),
        'VPt PM' => $xmlServices[0]->getAttribute('external_factor'),
        'montant' => $xmlServices[0]->getAttribute('amount'),
        'Tocilizumab' => $xmlServices[0]->getAttribute('name'),
        // ligne 2 -> bas de page
        'provider_id_2' => $xmlServices[1]->getAttribute('provider_id'),// use variable[1] and getAttribute to get same name for data
        'responsible_id_2' => $xmlServices[1]->getAttribute('responsible_id'),
        'date_begin_2' => $xmlServices[1]->getAttribute('date_begin'),
        'tariff_type_2' => $xmlServices[1]->getAttribute('tariff_type'),
        'code_2' => $xmlServices[1]->getAttribute('code'),
        'session_2' => $xmlServices[1]->getAttribute('session'),
        'quantité_2' => $xmlServices[1]->getAttribute('quantity'),
        'Pt PM/Prix_2' => $xmlServices[1]->getAttribute('unit'),
        'VPt_PM_2' => $xmlServices[1]->getAttribute('external_factor'),
        'montant_2' => $xmlServices[1]->getAttribute('amount'),
        'réadaptation' => $xmlServices[1]->getAttribute('name'),
      ];

      // Prepare result.
      $result = [
        'payload' => $payload,
        'body' => $body,
        'biller' => $biller,
        'invoice' => $invoice,
        'debitor' => $debitor,
        'provider' => $provider,
        'patient' => $patient,
        'balance' => $balance,
        'esrQR' => $esrQR,
        'kgv' => $kgv,
        'treatment' => $treatment,
        'services' => $services,
      ];

      return $result;

    }
    catch (\Exception $e) {
        throw($e);
    }

  }

}