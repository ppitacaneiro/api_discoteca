<?php

class Validator {

    public function validateDate($date) {

        $isDateValid = false;

        $dateArray = explode('/',$date);
        if (count($dateArray) == 3) {
            if (checkdate($dateArray[1],$dateArray[0],$dateArray[2])) {
                $isDateValid = true;
            }
        }

        return $isDateValid;
    }

}

?>