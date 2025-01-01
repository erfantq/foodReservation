let startWeekDate;
let endWeekDate;
let date = new Date();
let selectedDayDate = date;

let dayGlob = date.getDay();
if (dayGlob == 4 || dayGlob == 5) {
    dayGlob = 6;
}
let lastSelectedDay = document.getElementById("day" + dayGlob);
let lastType = 'food';
document.getElementById("foodLink").style.borderColor = 'white';
lastSelectedDay.style.color = "#dc3545";

function getWeek(startWeekDate, endWeekDate) {
    console.log(startWeekDate);
    console.log(endWeekDate);
    this.startWeekDate = new Date(startWeekDate);
    this.endWeekDate = new Date(endWeekDate);
    
    jalaaliStart = this.startWeekDate.toLocaleDateString('fa-IR');
    jalaaliEnd = this.endWeekDate.toLocaleDateString('fa-IR');

    const dateDisplay = document.getElementById("dateDisplay");
    dateDisplay.innerText = jalaaliStart + ' -- ' + jalaaliEnd;
}

async function getReservedFoods(event, day) {
    event.preventDefault();

    if (day != dayGlob) {
        if(day == 6) {
            selectedDayDate = this.startWeekDate;

        } else {
            if(day < dayGlob) {
                selectedDayDate =  subtractDays(selectedDayDate, dayGlob-day);
            } else {
                selectedDayDate =  addDays(selectedDayDate, day-dayGlob);
            }
        }
        dayGlob = day;
        console.log(dayGlob);
    }
    
    lastSelectedDay.style.color = "rgba(255, 255, 255, 0.55)";
    event.target.style.color = "#dc3545";
    lastSelectedDay = event.target;

    document.getElementById("mySpinner").style.display = 'flex';

    const mainElem = document.querySelector("main");
    mainElem.innerHTML = "";


    let fetchedData = await fetch("reservedFoodsMain.php?day=" + dayGlob + "&type=" + lastType);

    let textedData = await fetchedData.text();

    document.getElementById("mySpinner").style.display = 'none';

    mainElem.innerHTML = textedData;

}

async function getType(event, type) {
    event.preventDefault();

    if(type == 'food') {
        lastType = 'food';
        document.getElementById("drinkLink").style.borderColor = 'rgb(33, 37, 41)';
        document.getElementById("foodLink").style.borderColor = 'white';  
    } else {
        lastType = 'drink';
        document.getElementById("drinkLink").style.borderColor = 'white';
        document.getElementById("foodLink").style.borderColor = 'rgb(33, 37, 41)';
    }
    
    document.getElementById("mySpinner").style.display = 'flex';

    const mainElem = document.querySelector("main");
    mainElem.innerHTML = "";

    let fetchedData = await fetch("reservedFoodsMain.php?day=" + dayGlob + "&type=" + lastType);

    let textedData = await fetchedData.text();

    document.getElementById("mySpinner").style.display = 'none';
    
    mainElem.innerHTML = textedData;

}

async function weekChanger(event, value) {
    event.preventDefault();
    
    document.getElementById("mySpinner").style.display = 'flex';


    if(value == 1) {
        startWeekDate = addDays(this.startWeekDate, 7);
        endWeekDate = addDays(this.endWeekDate, 7);

    } else {
        startWeekDate = subtractDays(this.startWeekDate, 7);
        endWeekDate = subtractDays(this.endWeekDate, 7);
    }

    jalaaliStart = startWeekDate.toLocaleDateString('fa-IR');
    jalaaliEnd = endWeekDate.toLocaleDateString('fa-IR');

    const dateDisplay = document.getElementById("dateDisplay");
    dateDisplay.innerText = jalaaliStart + ' -- ' + jalaaliEnd;

    const mainElem = document.querySelector("main");
    mainElem.innerHTML = "";

    let fetchedData = await fetch("reservedFoodsMain.php?day=" + dayGlob + "&type=" + lastType + "&week=" + value);

    let textedData = await fetchedData.text();

    document.getElementById("mySpinner").style.display = 'none';
    
    mainElem.innerHTML = textedData;
}

function addDays(startDate, d) {
    startDate.setTime(startDate.getTime() 
    + (d * 24 * 60 * 60 * 1000));
    return startDate;
}
function subtractDays(startDate, d) {
    startDate.setTime(startDate.getTime() 
    - (d * 24 * 60 * 60 * 1000));
    return startDate;
}