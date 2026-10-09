export interface MockUser {
  username: string
  firstName: string
  age: number
  fameRating: number
  city: string
  distanceKm: number
  tags: string[]
  avatar: string
}

export const MOCK_TAGS = [
  'vegan',
  'geek',
  'sport',
  'voyage',
  'musique',
  'cinéma',
  'gaming',
  'cuisine',
  'art',
  'lecture',
  'randonnée',
  'photo',
]

export const MOCK_LOCATIONS = [
  { label: 'Autour de moi', value: 'nearby' },
  { label: 'Paris', value: 'Paris' },
  { label: 'Lyon', value: 'Lyon' },
  { label: 'Marseille', value: 'Marseille' },
  { label: 'Bordeaux', value: 'Bordeaux' },
  { label: 'Lille', value: 'Lille' },
]

const names = [
  'Emma',
  'Lucas',
  'Léa',
  'Hugo',
  'Chloé',
  'Nathan',
  'Manon',
  'Louis',
  'Camille',
  'Jules',
  'Sarah',
  'Tom',
]
const cities = ['Paris', 'Lyon', 'Marseille', 'Bordeaux', 'Lille']

export const MOCK_USERS: MockUser[] = Array.from({ length: 40 }, (_, i) => {
  const name = names[i % names.length]
  return {
    username: `${name.toLowerCase()}${i}`,
    firstName: name,
    age: 18 + ((i * 7) % 40),
    fameRating: (i * 13) % 101,
    city: cities[i % cities.length],
    distanceKm: (i * 17) % 120,
    tags: MOCK_TAGS.filter((_, t) => (i + t) % 4 === 0).slice(0, 4),
    avatar: `https://i.pravatar.cc/150?img=${(i % 70) + 1}`,
  }
})
