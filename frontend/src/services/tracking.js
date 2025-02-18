import { Api } from '@/utils/api.js'

export class Tracking {
  static setAtCurrentLocation = async () => {
    await Api.put('/users/me/localisation').send()

    navigator.geolocation.getCurrentPosition(loc => {
      Api.put('/users/me/localisation').send({
        lat: loc.coords.latitude,
        lon: loc.coords.longitude,
      })
    })
  }

  static getPositionInfoByLatLon = async (lat, lon) => {
    let res = await fetch(
      `http://api.geonames.org/findNearbyJSON?lat=${lat}&lng=${lon}&username=example`,
    )
    res = await res.json()
    res = {
      countryCode: res.geonames[0].countryCode,
      name: res.geonames[0].toponymName,
    }
    return res
  }

  static getCityListByName = async (city, countryCode) => {
    let res = await fetch(
      `http://api.geonames.org/searchJSON?name_startsWith=${city}&country=${countryCode}&featureClass=P&maxRows=10&username=example`,
    )
    res = await res.json()
    return res.geonames
  }
}
