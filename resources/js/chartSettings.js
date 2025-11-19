// console.log("first message")
const fileExtinsion = `Refada-${new Date().getTime()}`;
let opacity = 0.5;
let toolbarSettings = {
    show: true,
    tools: {
        download: true,
    },
    export: {
        csv: {
            filename: fileExtinsion
        },
        svg: {
            filename: fileExtinsion
        },
        png: {
            filename: fileExtinsion
        }
    }
}

let locales = {
    name: 'custom',
    options: {
        toolbar: {
            "exportToSVG": "SVG تحميل بصيغة",
            "exportToPNG": "PNG تحميل بصيغة",
            "exportToCSV": "CSV تحميل بصيغة",
        }
    }
}
const CHARTHEIGHT = 350;

let chartColors = [
    '#FFEEC7',
    '#A09A8F',
    '#CAB272',
    '#665D4C',
    '#332C20',
]

const CHART = {
    height: CHARTHEIGHT,
    fontFamily: 'IBM Plex Sans Arabic',
    toolbar: toolbarSettings,
    locales: [locales],
    defaultLocale: 'custom'
}
