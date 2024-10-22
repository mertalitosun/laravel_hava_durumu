<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset("css/app.css")}}">
    <title>Hava Durumu</title>
</head>
<body>
    <div class="container my-5 d-flex align-items-center justify-content-center">
        <div class="weather-card">
            <div class="card-body my-3">
                <img src="" alt="" id="weatherIcon">
                <div class="cityName" id="cityName"></div>
                <div class="weatherDegree" id="weatherDegree"></div>
                <div class="weather" id="weather"></div>
            </div>
            <div class="weather-details">
                <div class="row">
                    <div class="col-6 col-md-6">
                        <div class="detail-cards">
                            <img src="https://openweathermap.org/img/wn/50d.png" alt="">
                            <div class="detail-card-title">Rüzgar</div>
                            <div id="wind" class="detail-card-description"></div>
                        </div>
                    </div>
                    <div class="col-6 col-md-6">
                        <div class="detail-cards">
                            <div>%</div>
                            <div class="detail-card-title">Nem</div>
                            <div id="humidity" class="detail-card-description"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 col-md-6">
                        <div class="detail-cards">
                            <img src="https://openweathermap.org/img/wn/01d.png" alt="">
                            <div class="detail-card-title">Gün Doğumu</div>
                            <div id="sunrise" class="detail-card-description"></div>
                        </div>
                    </div>
                    <div class="col-6 col-md-6">
                        <div class="detail-cards">
                            <img src="https://openweathermap.org/img/wn/01n.png" alt="">
                            <div class="detail-card-title">Gün Batımı</div>
                            <div id="sunset" class="detail-card-description"></div>
                        </div>
                    </div>
                </div>
               
            </div>
            <select class="form-select" id="city">
                <option selected>Şehir Seçin</option>
            </select>
        </div>
    </div>
    <script>
        const defaultCity = "Denizli"
        const citySelect = document.getElementById("city");
        const weatherDegree = document.getElementById("weatherDegree");
        const weather = document.getElementById("weather");
        const cityName = document.getElementById("cityName");
        const weatherIcon = document.getElementById("weatherIcon");
        const weatherDetails = document.querySelector(".weather-details");

        const wind = document.getElementById("wind");
        const humidity = document.getElementById("humidity");
        const sunrise = document.getElementById("sunrise");
        const sunset = document.getElementById("sunset");
        
        fetch("https://turkiyeapi.dev/api/v1/provinces",{
            method:"GET",
            headers:{
                "Content-Type" : "application/json"
            }
        })
        .then(response => response.json())
        .then(data => {
            data.data.forEach(city => {
                const option = document.createElement("option");
                option.value = city.name;
                option.innerText = city.name;
                citySelect.appendChild(option)
            });

            // hava durumu api
            const weatherApi = (lat,lon) =>{
                fetch(`https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&appid=0de8d723d29b6378c6d4154786e3c827&units=metric&lang=tr`)
                .then(response => response.json())
                .then(data => {
                    weatherDegree.innerText = `${Math.round(data.main.temp)}°C`;
                    weather.innerText = data.weather[0].description;
                    weatherIcon.src = `https://openweathermap.org/img/wn/${data.weather[0].icon}@2x.png`

                    wind.innerText = Math.round(data.wind.speed);
                    humidity.innerText = `${Math.round(data.main.humidity)}`;

                    const formatDate = (time) => {
                        const date = new Date(time * 1000);
                        const hours = date.getHours().toString().padStart(2,"0");
                        const minutes = date.getMinutes().toString().padStart(2,"0");

                        return formattedTime = `${hours}:${minutes}`;
                    }
                    
                    sunrise.innerText = formatDate(data.sys.sunrise);
                    sunset.innerText = formatDate(data.sys.sunset);
                })
                .catch(err => console.log(err))
            }

            citySelect.value = defaultCity;
            cityName.innerText = defaultCity;
            let cityData = data.data.find(city => city.name === defaultCity);
            let lat = cityData.coordinates.latitude
            let lon = cityData.coordinates.longitude
            weatherApi(lat,lon);
            
            citySelect.addEventListener("change",()=>{
                const selectedCity = city.value;
                cityName.innerText = selectedCity;
                cityData = data.data.find(city => city.name === selectedCity);
                lat = cityData.coordinates.latitude;
                lon = cityData.coordinates.longitude;
                weatherApi(lat,lon);
            })
        })
        .catch(err => console.log(err))
    </script>
</body>
</html>