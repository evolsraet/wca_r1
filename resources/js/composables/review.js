import { ref } from 'vue';

export function useReview() {
  const rating = ref(4);
//rating초기값을 4점으로 임의 설정

  function setRating(newRating) {
    rating.value = newRating;
  }

  return { rating, setRating };
}
