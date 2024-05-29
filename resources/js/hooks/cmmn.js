export function cmmn() {
    const numberToKoreanUnit = (input) =>  {
        const units = ["", "만", "억", "조", "경"];
        input = parseInt(input.replace(/[^0-9]/g, ""));
        if (isNaN(input)) return "";
        
        let result = '';
        let unitIndex = 0;
        
        while (input > 0) {
            let part = input % 10000;
            if (part > 0) {
            result = part + (units[unitIndex] ? " " + units[unitIndex] : "") + " " + result;
            }
            input = parseInt(input / 10000);
            unitIndex++;
        }
        
        return result.trim();
      
    }

    const amtComma = (amt) => {
        return amt.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    return {
      numberToKoreanUnit,
      amtComma
    }
  }
