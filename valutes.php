<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * класс Valutes - вывод валют EUR и USD
 *
 * Автор: Андрей Верстов
 */

class Valutes
{
    /**
     * @param bool $json
     * @return array
     */
    public function getCourses($json = true)
    {
        $usd = $this->_getValutesXml('R01235');
        $eur = $this->_getValutesXml('R01239');

        $result = array('dt' => date("d") . '.' . date("m") . '.' . date("Y"));

        $a['code'] = 'USD';
        $a['tomorrow'] = sprintf('%0.2f', $usd[1]);
        $a['delta'] = str_replace(",", ".", sprintf('%0.2f', $usd[1] - $usd[0]));
        array_push($result, $a);

        $a['code'] = 'EUR';
        $a['tomorrow'] = sprintf('%0.2f', $eur[1]);
        $a['delta'] = str_replace(",", ".", sprintf('%0.2f', $eur[1] - $eur[0]));
        array_push($result, $a);

        if($json){
            $result = json_encode($result);
        }

        return $result;
    }

    /**
     * @param int $id
     * @return array
     */
    private function _getValutesXml($id = 0)
    {
        $result = array();

        $yesterday = (date("d") - 1) . '/' . date("m") . '/' . date("Y");
        $today = date("d") . '/' . date("m") . '/' . date("Y");

        $xml = new XMLReader();
        $xml->open('http://www.cbr.ru/scripts/XML_dynamic.asp?date_req1=' . $yesterday . '&date_req2=' . $today . '&VAL_NM_RQ=' . $id);

        while ($xml->read()) {

            if ($xml->name == "Record" && $xml->nodeType != XMLREADER::END_ELEMENT) {

                while ($xml->read()) {

                    if ($xml->name == "Value" && $xml->nodeType != XMLREADER::END_ELEMENT) {
                        $result[] = str_replace(",", ".", $xml->readString());
                    }

                }

            }

        }

        $xml->close();

        return $result;
    }


}