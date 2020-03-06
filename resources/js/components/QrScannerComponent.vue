<template>
  <div>
    <p class="error">{{ error }}</p>



   <qrcode-stream :camera="camera" @decode="onDecode" @init="onInit">
        <div v-if="validationPending" class="validation-pending">
         Verificando...
      </div>
            <div v-if="validationFailure" class="validation-failure">
        Codigo invalido!
      </div>

      <div v-if="validationSuccess" class="validation-success">
        Perfecto!
      </div>

   </qrcode-stream>

  </div>
</template>

<script>
import { QrcodeStream } from 'vue-qrcode-reader'

export default {

  components: { QrcodeStream },

  data () {
    return {
      isValid: undefined,
      camera: 'auto',
      result: null,
      result: '',
      error: ''
    }
  },

  computed: {
    validationPending () {
      return this.isValid === undefined
        && this.camera === 'off'
    },

    validationSuccess () {
      return this.isValid === true

    },

    validationFailure () {
      return this.isValid === false
    }
  },
  methods: {
    async onDecode (result) {
      this.result = result
      this.turnCameraOff()

      // pretend it's taking really long
      await this.timeout(1500)
      this.isValid = result.startsWith('https://comandas.atendimientos.com/local/')

      // some more delay, so users have time to read the message
      await this.timeout(1000)
      if(this.isValid){
      window.location.href = result;
      }
      this.turnCameraOn()
    },
    timeout (ms) {
      return new Promise(resolve => {
        window.setTimeout(resolve, ms)
      })
    },
    turnCameraOn () {
      this.camera = 'auto'
    },

    turnCameraOff () {
      this.camera = 'off'
    },
    resetValidationState () {
      this.isValid = undefined
    },
    onInit (promise) {
      promise
        .catch(console.error)
        .then(this.resetValidationState)
    },
    /*async onInit (promise) {
      try {

      await promise

      this.turnCameraOff()


      } catch (error) {
        if (error.name === 'NotAllowedError') {
          this.error = "ERROR: Tienes que permitir el uso de la camara."
        } else if (error.name === 'NotFoundError') {
          this.error = "ERROR: No se detecto camara."
        } else if (error.name === 'NotSupportedError') {
          this.error = "ERROR: Conexión insegura."
        } else if (error.name === 'NotReadableError') {
          this.error = "ERROR: La camara ya esta en uso?"
        } else if (error.name === 'OverconstrainedError') {
          this.error = "ERROR: Tu camara no es compatible"
        } else if (error.name === 'StreamApiNotSupportedError') {
          this.error = "ERROR: Tu navegador no es compatible."
        }
      }
    }*/
  }
}
</script>

<style scoped>
.error {
  font-weight: bold;
  color: red;
}

.validation-success,
.validation-failure,
.validation-pending {
  position: absolute;
  width: 100%;
  height: 100%;

  background-color: rgba(255, 255, 255, .8);
  text-align: center;
  font-weight: bold;
  font-size: 1.4rem;
  padding: 10px;

  display: flex;
  flex-flow: column nowrap;
  justify-content: center;
}
.validation-success {
  color: green;
}
.validation-failure {
  color: red;
}
</style>