
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import draggable from 'vuedraggable';
import Vuetify from 'vuetify';

Vue.use(Vuetify);

Vue.component('tabs', {
    template: `
        <div>
            <div class="tabs is-toggle is-fullwidth is-medium">
                <ul>
                    <li v-for="tab in tabs" :class="{ 'is-active': tab.isActive }">
                        <a :href="tab.href" @click="selectTab(tab)">Step {{ tab.name }}</a>
                    </li>
                </ul>
            </div>
            <div class="tabs-details">
                <slot></slot>
            </div>
        </div>
    `,

    data() {
        return { tabs: [] };
    },

    created() {
        this.tabs = this.$children;
    },

    methods: {
        selectTab(selectedTab) {
            this.tabs.forEach(tab => {
                tab.isActive = (tab.href == selectedTab.href);
            });
        }
    }
});

Vue.component('tab', {
    template: `
        <div v-show="isActive"><slot></slot></div>
    `,

    props: {
        name: { required: true },
        selected: { default: false }
    },

    data() {
        return {
            isActive: false
        };
    },

    computed: {
        href() {
            return '#' + this.name.toLowerCase().replace(/ /g, '-');
        }
    },

    mounted() {
        this.isActive = this.selected;
    },
});

new Vue({

	el: '#app',

	data: {
 
		e1: 0,
    	forms: [],
    	form: [],
      formV: [],
      formCompare: [],
    	formTransfer: [],
      formTransferV: [],
    	showUpdateForm: false,
    	showEditForm: false,
      showCreateFlow: false,
      showEditFlow: false,
      confirmVariant: [],
      variants: {},
      variantsResponse: {},
    	formSelect: '',
    	variant: {"steps": {}},
      variantV: {"steps": {}},
      fieldlist: [],
    	defaultField: {"polsone__name":null,"polsone__type":"text","polsone__required":false,"polsone__sometimes":false,"polsone__validation":[],"frontend__toggles":[],"frontend__label":null,"frontend__value":null,"frontend__options":null,"frontend__placeholder":null,"frontend__help_text":null,"frontend__heading":null,"frontend__section":null,"frontend__hidden":null},

	},

	components: {
		draggable
	},

  mounted() {

		axios.get('http://staging.polsone.cds-store.com/api/v1/forms/').then( response => this.forms = response.data );
    axios.get('/api/variants').then(response => this.variants = response.data.data);
    axios.get('http://staging.polsone.cds-store.com/api/v1/fields').then( response => this.fieldlist = this.listFields(response.data) );

	},

	methods: {

    listFields(fieldsObjects) {

      let tempFields = [];

      for (var fieldObject in fieldsObjects) {

        fieldsObjects[fieldObject].forEach(function(value, i) {

          tempFields.push({"fieldname":value,"fieldtype":fieldObject});

        });

      }

      return tempFields;

    },

    markForms(current, original) {

      let fieldlist = [];
      let fieldsublist = [];

      // Build fieldlist
      original.data.form_json.steps.forEach(function(value, i) {

        value.forEach(function(valuetwo, itwo) {

          for (var key in valuetwo) {

            fieldlist.push(key);

            for (var subkey in valuetwo[key]) {

              fieldsublist.push(key + "___" + subkey);

            }

          };


        });

      });


      // Compare current form fields to fieldlist and mark
      for (var currentkey in current.steps) {

        current.steps[currentkey].fields.forEach(function(currentvalue, currenti) {

          if(fieldlist.includes(currentvalue.polsone__name)) {

            currentvalue['updated'] = 1;

          }

          // Subfield setting
          if(fieldsublist.includes(currentvalue.polsone__name)) {

            // Set the subfield
            

          }

          fieldsublist.forEach(function(fsvalue, fsi){

            if(fsvalue.includes(currentvalue.polsone__name)) {

              let str = fsvalue;
              let slug = str.split('___').pop();

              if(currentvalue['updated_fields']) {
                currentvalue['updated_fields'].push(slug);
              } else {
                currentvalue['updated_fields'] = [];
                currentvalue['updated_fields'].push(slug);
              }
              

            }

          });



        });

      };
      

    },

		changedForm(index, id) {

      if (id == 1) {
        axios.get('http://staging.polsone.cds-store.com/api/v1/forms/step-forms/' + this.formSelect).then( response => {this.form = response.data, this.formTransfer = response.data} );
        this.e1 = index;
      } else if (id == 2) {
        axios.get('/api/variant/' + this.formSelect).then( response => {this.formV = response.data.data.compiled_form, this.formCompare = response.data, this.formTransferV = response.data.compiled_form} );
        this.e1 = index;
      }

		},

		pickedAction(action, id) {
      //TODO - Clean up
			if ( action == 'update' ) {

				this.showUpdateForm = true;
				this.showEditForm   = false;
        this.e1 = 4;

        if (this.showEditFlow) {
          this.markForms(this.formV, this.formCompare)
        }

			} else if ( action == 'edit' ) {

				this.showUpdateForm = false;
				this.showEditForm   = true;
        this.e1 = 4;

        if (this.showEditFlow) {
          this.markForms(this.formV, this.formCompare)
        }

			} else if ( action == 'create' ) {

        this.showCreateFlow = true;
        this.showEditFlow = false;
        this.e1 = 2;

      } else if ( action == 'editVariant' ) {

        this.showCreateFlow = false;
        this.showEditFlow = true;
        this.e1 = 2;

      }

		},

		getIndex(id) {

  			if(id == 0) {
  				return true;
  			} else {
  				return false;
  			}

  		},

  		changedPosition(stepkey, stepvalue, stepindex) {

  			this.form.steps[stepkey].fields.forEach( function(value, i) {
  				value.frontend__order = i;
  			});

  		},

      changedPositionEditFlow(stepkey, stepvalue, stepindex) {

        this.formV.steps[stepkey].fields.forEach( function(value, i) {
          value.frontend__order = i;
        });

      },

  		saveVariant() {

  			this.variant = {"steps": {}};
  			var compareForm = this.formTransfer;
			  var tempStepObject = {};

			function transformFieldsToEachStep(key, form, variant) {

				variant.steps[key] = [];

				form.steps[key].fields.forEach(function(value, i) {

					if (value.updated) {

					} else {
						value['upated'] = 0;
					}

					if (value.updated) {

						let tempFieldVar = {};
						tempFieldVar[value.polsone__name] = {};

						value.updated_fields.forEach(function(paramValue, parami) {

							

							if (value.hasOwnProperty(paramValue) && paramValue != 'polsone__name') {

								tempFieldVar[value.polsone__name][paramValue] = value[paramValue];
							}	

							
						});

						variant.steps[key].push(tempFieldVar);

					} 
					

				});

				return variant.steps[key];


			}


			// Map Steps
  			for (var key in this.form.steps) {
			    if (this.form.steps.hasOwnProperty(key)) {

			    	// Key = Step 1,2,3,4 etc
			        tempStepObject[key] = [];

			        this.variant.steps[key] = transformFieldsToEachStep(key, this.form, this.variant);

			    }
			}

      // Send to Polsone
			axios.post('/api/variant/', {
        slug: this.form.slug,
        id: this.formSelect,
        description: this.form.description,
        steps: this.variant
      })
      .then(response => {console.log(this.response)})
      .catch(e => {
        this.errors.push(e)
      });

      // Go to next step
      //this.e1 = 5;

      this.startOver();
			

  		},

      saveVariantEditFlow() {
        //TODO - Look to eventually merge these two workflows into one.
        this.variantV = {"steps": {}};
        var compareFormV = this.formTransferV;
        var tempStepObjectV = {};

        function transformFieldsToEachStepV(keyV, formV, variantV) {



        variantV.steps[keyV] = [];

        formV.steps[keyV].fields.forEach(function(value, i) {

          if (value.updated) {

          } else {
            value['upated'] = 0;
          }

          // If field has been updated
          if (value.updated) {

            let tempFieldVar = {};
            tempFieldVar[value.polsone__name] = {};

            // If Param has been updated
            value.updated_fields.forEach(function(paramValue, parami) { 

              if (value.hasOwnProperty(paramValue) && paramValue != 'polsone__name') {

                tempFieldVar[value.polsone__name][paramValue] = value[paramValue];
              } 

              
            });

            variantV.steps[keyV].push(tempFieldVar);

          } 
          

        });

        return variantV.steps[keyV];

        // Every Field of Every Step

      }


      // Map Steps
        for (var keyV in this.formV.steps) {
          if (this.formV.steps.hasOwnProperty(keyV)) {

            // Key = Step 1,2,3,4 etc
              tempStepObjectV[keyV] = [];

              this.variantV.steps[keyV] = transformFieldsToEachStepV(keyV, this.formV, this.variantV);

          }
      }

      console.log(JSON.stringify(this.variantV, null, 2));

      // Go to next step
      //this.e1 = 5;

      // Send to Polsone
      axios.post('/api/variantedit/', {
        slug: this.formCompare.data.slug,
        id: this.formCompare.data.id,
        form_id: this.formCompare.data.form_id,
        description: this.formCompare.data.description,
        steps: this.variantV
      })
      .then(response => {console.log(this.response)})
      .catch(e => {
        this.errors.push(e)
      });

      this.startOver();


      },

  		checkMove(evt) {

  			// Set dragged Field as updated
  			evt.draggedContext.element['updated'] = 1;

  			if (evt.draggedContext.element['updated_fields']) {
  				evt.draggedContext.element['updated_fields'].push('frontend__order');
  			} else {
  				evt.draggedContext.element['updated_fields'] = [];
  				evt.draggedContext.element['updated_fields'].push('frontend__order');
  			}

  			// Set Related (the one that got swapped in the drag) Field as updated
  			evt.relatedContext.element['updated'] = 1;

  			if (evt.relatedContext.element['updated_fields']) {
  				evt.relatedContext.element['updated_fields'].push('frontend__order');
  			} else {
  				evt.relatedContext.element['updated_fields'] = [];
  				evt.relatedContext.element['updated_fields'].push('frontend__order');
  			}


  		},

  		changedField(fieldvalue, fieldname,) {

  			// Set field as updated
  			fieldvalue['updated'] = 1;

  			// Set Field as updated
  			if (fieldvalue['updated_fields']) {
  				fieldvalue['updated_fields'].push(fieldname);
  				fieldvalue['updated_fields'].push('frontend__order');
  			} else {
  				fieldvalue['updated_fields'] = [];
  				fieldvalue['updated_fields'].push(fieldname);
  				fieldvalue['updated_fields'].push('frontend__order');
  			}

        if(fieldname == 'polsone__name') {

          this.fieldlist.forEach(function(value, i){

            if(value.fieldname == fieldvalue.polsone__name) {
              fieldvalue.polsone__type = value.fieldtype;
            }

          });

        }
  			
  		},

  		addField(index, value) {

  			let tempIndex = index + 1;
  			let tempOrder = this.form.steps[tempIndex].fields.length;

  			this.form.steps[tempIndex].fields.push({"polsone__name":null,"polsone__type":"text","polsone__required":false,"polsone__sometimes":false,"polsone__validation":[],"frontend__toggles":[],"frontend__label":null,"frontend__value":null,"frontend__options":null,"frontend__placeholder":null,"frontend__help_text":null,"frontend__heading":null,"frontend__section":null,"frontend__hidden":null,"frontend__choices":false,"frontend__choices_allow_multiple":false,"frontend__choices_locked":true,frontend__order:tempOrder});

  		},

      addFieldEditFlow(index, value) {

        let tempIndex = index + 1;
        let tempOrder = this.formV.steps[tempIndex].fields.length;

        this.formV.steps[tempIndex].fields.push({"polsone__name":null,"polsone__type":"text","polsone__required":false,"polsone__sometimes":false,"polsone__validation":[],"frontend__toggles":[],"frontend__label":null,"frontend__value":null,"frontend__options":null,"frontend__placeholder":null,"frontend__help_text":null,"frontend__heading":null,"frontend__section":null,"frontend__hidden":null,"frontend__choices":false,"frontend__choices_allow_multiple":false,"frontend__choices_locked":true,frontend__order:tempOrder});

      },

  		addToggleField(stepindex, fieldindex) {

  			let tempStepIndex = stepindex + 1;
  			let tempFieldIndex = fieldindex;

  			this.form.steps[tempStepIndex].fields[tempFieldIndex].frontend__toggles[0].criteria.push("");

  		},

      addToggleFieldEditFlow(stepindex, fieldindex) {

        let tempStepIndex = stepindex + 1;
        let tempFieldIndex = fieldindex;

        this.formV.steps[tempStepIndex].fields[tempFieldIndex].frontend__toggles[0].criteria.push("");

      },

  		addToggle(stepindex, fieldindex) {

  			let tempStepIndex = stepindex + 1;
  			let tempFieldIndex = fieldindex;

  			this.form.steps[tempStepIndex].fields[tempFieldIndex].frontend__toggles.push({"display":"","multiple":"","criteria":[]})

  		},

      addToggleEditFlow(stepindex, fieldindex) {

        let tempStepIndex = stepindex + 1;
        let tempFieldIndex = fieldindex;

        this.formV.steps[tempStepIndex].fields[tempFieldIndex].frontend__toggles.push({"display":"","multiple":"","criteria":[]})

      },

      addOption(stepindex, fieldindex) {

        let tempStepIndex = stepindex + 1;
        let tempFieldIndex = fieldindex;

        if (this.form.steps[tempStepIndex].fields[tempFieldIndex].frontend__options) {
          this.form.steps[tempStepIndex].fields[tempFieldIndex].frontend__options.push({"key":"","text":""})
        } else {
          this.form.steps[tempStepIndex].fields[tempFieldIndex].frontend__options = [{"key":"","text":""}];
        }
        

      },

      addOptionEditFlow(stepindex, fieldindex) {

        let tempStepIndex = stepindex + 1;
        let tempFieldIndex = fieldindex;

        if (this.formV.steps[tempStepIndex].fields[tempFieldIndex].frontend__options) {
          this.formV.steps[tempStepIndex].fields[tempFieldIndex].frontend__options.push({"key":"","text":""})
        } else {
          this.formV.steps[tempStepIndex].fields[tempFieldIndex].frontend__options = [{"key":"","text":""}];
        }
        

      },

      startOver() {

        location.reload();

      }

	}
  


})

