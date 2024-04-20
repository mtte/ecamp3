import Vue from 'vue'
import Vuetify from 'vuetify'

import formBaseComponents from '@/plugins/formBaseComponents'

import { mount as mountComponent } from '@vue/test-utils'
import ENumberField from '../ENumberField.vue'

Vue.use(Vuetify)
Vue.use(formBaseComponents)

describe('An ENumberField', () => {
  let vuetify

  const mount = (options) => {
    const app = Vue.component('App', {
      components: { ENumberField },
      data: function () {
        return {
          data: null,
        }
      },
      template: `<div data-app><e-number-field label="test" v-model.number="data"/></div>`,
    })
    return mountComponent(app, { vuetify, attachTo: document.body, ...options })
  }

  beforeEach(() => {
    vuetify = new Vuetify()
  })

  test('looks like a numberfield', async () => {
    const wrapper = mount()
    expect(wrapper).toMatchSnapshot('empty')

    await wrapper.setData({ data: 3.14 })
    expect(wrapper).toMatchSnapshot('with text')
  })

  test('updates text when vModel changes', async () => {
    const wrapper = mount()
    expect(wrapper.find('.e-form-container').element.getAttribute('value')).toBeNull()

    const firstNumber = 0
    await wrapper.setData({ data: firstNumber })
    expect(wrapper.find('.e-form-container').element.getAttribute('value')).toBe(
      `${firstNumber}`
    )

    const secondNumber = 3.14
    await wrapper.setData({ data: secondNumber })
    expect(wrapper.find('.e-form-container').element.getAttribute('value')).toBe(
      `${secondNumber}`
    )
  })

  test('updates vModel when value of input field changes', async () => {
    const wrapper = mount()
    const input = wrapper.find('input')
    const number = 3.14

    input.element.value = `${number}`
    await input.trigger('input')

    expect(wrapper.vm.data).toBe(number)
  })

  test.each([
    ['1', 1],
    ['1.', 1],
    ['1..2', 1.2],
    ['39.5.', 39.5],
    ["2'000", 2000],
    ['8.000.000,20', 8000000.2],
    ['10e3', 103],
    ['2kg', 2],
    ['8,000.20', 8000.2],
    ['abc123', 123],
    ['Hello, World?', ''],
    ['eCamp. Super!', '.'],
    ['+123..456..789', 123.456789],
    ['-10', -10],
    ['2-4 Stück', 24],
    ['-0', -0],
    ['.', '.'],
    ['.a', '.'],
    ['.a02', 0.02],
  ])('parses "%s" as "%s"', async (string, expected) => {
    const wrapper = mount()
    const input = wrapper.find('input')

    input.element.value = string
    await input.trigger('input')

    expect(wrapper.vm.data).toBe(expected)
  })
})
