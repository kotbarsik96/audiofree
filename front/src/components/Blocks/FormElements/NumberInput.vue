<template>
  <input
    type="text"
    inputmode="numeric"
    class="input number-input"
    @input="onInput"
  />
</template>

<script setup lang="ts">
import { computed } from "vue"

const props = withDefaults(
  defineProps<{
    min?: number
    max?: number
    maxFractionDigits?: number
  }>(),
  {
    min: 0,
  }
)

const emit = defineEmits<{
  (e: "input", value: number): void
}>()

const _maxFractionDigits = computed(() =>
  props.maxFractionDigits && props.maxFractionDigits > 0
    ? props.maxFractionDigits
    : 0
)
const symbolsRegexp = computed(() => {
  if (props.min < 0 && props.maxFractionDigits) return /[^-0-9.]/g
  else if (props.min >= 0 && props.maxFractionDigits) return /[^0-9.]/g
  else if (props.min < 0 && !props.maxFractionDigits) return /[^-0-9]/g
  return /[^0-9]/g
})

function toNumber(num: string) {
  return _maxFractionDigits.value ? parseFloat(num) : parseInt(num)
}
function onInput(event: Event) {
  const target = event.target as HTMLInputElement
  let value = target.value.trim()
  const number = toNumber(value)
  let emmitableNumber = number

  handleSymbols()
  if (!value || value.match(/(^-$)|(\.$)/)) {
    target.value = value
    return
  }
  handleMaxFractionDigits()
  handleMinMax()

  target.value = emmitableNumber.toString()
  emit("input", emmitableNumber)

  function handleSymbols() {
    value = value.replace(symbolsRegexp.value, "")
    emmitableNumber = toNumber(value)
  }
  function handleMaxFractionDigits() {
    if (!_maxFractionDigits.value) return

    const fractionDigits = emmitableNumber.toString().split(".")[1]?.length || 0
    if (fractionDigits > _maxFractionDigits.value)
      emmitableNumber = toNumber(
        emmitableNumber.toFixed(_maxFractionDigits.value)
      )
  }
  function handleMinMax() {
    let moreThanMin = false
    let lessThenMax = false

    if (props.min || props.min === 0) {
      if (emmitableNumber >= props.min) moreThanMin = true
    }
    if (props.max || props.max === 0) {
      if (emmitableNumber <= props.max) lessThenMax = true
    }

    if (!moreThanMin && (props.min || props.min === 0))
      emmitableNumber = props.min
    if (!lessThenMax && (props.max || props.max === 0))
      emmitableNumber = props.max
  }
}
</script>

<style lang="scss"></style>
