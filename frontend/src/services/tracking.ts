import { Api } from '@/utils/api'
import type { GeoPositionInfo, CityInfo } from '@/types'

export class Tracking {
  static setAtCurrentLocation = async (): Promise<void> => {
    await Api.put('/users/me/localisation').send()

    navigator.geolocation.getCurrentPosition(loc => {
      Api.put('/users/me/localisation').send({
        lat: loc.coords.latitude,
        lon: loc.coords.longitude,
      })
    })
  }

  static getPositionInfoByLatLon = async (
    lat: number,
    lon: number,
  ): Promise<GeoPositionInfo> => {
    const res = await fetch(
      `http://api.geonames.org/findNearbyJSON?lat=${lat}&lng=${lon}&username=example`,
    )
    const data = await res.json()
    return {
      countryCode: data.geonames[0].countryCode,
      name: data.geonames[0].toponymName,
    }
  }

  static getCityListByName = async (
    city: string,
    countryCode: string,
  ): Promise<CityInfo[]> => {
    const res = await fetch(
      `http://api.geonames.org/searchJSON?name_startsWith=${city}&country=${countryCode}&featureClass=P&maxRows=10&username=example`,
    )
    const data = await res.json()
    return data.geonames
  }
}
