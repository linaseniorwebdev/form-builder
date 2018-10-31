<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
        <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet">
        <link href="https://unpkg.com/vuetify/dist/vuetify.min.css" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
        <link rel="stylesheet" type="text/css" href='{{ asset("css/app.css") }}'>

    </head>
    <body>

        <div id="app">
          <v-app id="inspire">
            <v-stepper v-model="e1">
              <v-stepper-header>
                <v-stepper-step :complete="e1 > 1" step="1">Create or Edit</v-stepper-step>
                <v-divider></v-divider>
                <v-stepper-step :complete="e1 > 2" step="2">Pick a Form</v-stepper-step>
                <v-divider></v-divider>
                <v-stepper-step :complete="e1 > 3" step="3">Quick or Full</v-stepper-step>
                <v-divider></v-divider>
                <v-stepper-step step="4">Edit</v-stepper-step>
              </v-stepper-header>

              <v-stepper-items>
                <v-stepper-content step="1">
                  <v-card class="mb-5" height="200px">
                    <div>
                      <v-btn color="info" @click.prevent="pickedAction('create',1)">Create New Variant</v-btn>
                      <v-btn color="info" @click.prevent="pickedAction('editVariant',2)">Edit Variant</v-btn>
                    </div>
                  </v-card>
                </v-stepper-content>
                <v-stepper-content step="2">
                  <v-card class="mb-5" height="200px">
                    <div class="field" v-if="showCreateFlow">
                      <label class="label">Select a base form</label>
                      <div class="control">
                        <div class="select">
                          <select v-model="formSelect" v-on:change="changedForm(3,1)">
                            <option></option>
                            <option v-for="(value, key, index) in forms['step-forms']" v-text="key"></option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="field" v-if="showEditFlow">
                      <label class="label">Select a variant</label>
                      <div class="control">
                        <div class="select">
                          <select v-model="formSelect" v-on:change="changedForm(3,2)">
                            <option></option>
                            <option v-for="(vvalue, vkey, vindex) in variants" :value="vvalue.id">@{{ vvalue.slug }}</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </v-card>
                </v-stepper-content>
                <v-stepper-content step="3">
                  <v-card class="mb-5" height="200px">
                    <div>
                      <v-btn color="info" @click.prevent="pickedAction('update')">Quick Mode</v-btn>
                      <v-btn color="info" @click.prevent="pickedAction('edit')">Full Mode</v-btn>
                    </div>
                  </v-card>
                  
                </v-stepper-content>
                <v-stepper-content step="4">
                  
                  <div class="field" v-if="showCreateFlow" style="margin-top:18px;margin-bottom:18px;" @click.prevent="saveVariant()">
                    <div class="control">
                      <button class="button is-link">Save Form</button>
                    </div>
                  </div>

                  <div class="field" v-if="showEditFlow" style="margin-top:18px;margin-bottom:18px;" @click.prevent="saveVariantEditFlow()">
                    <div class="control">
                      <button class="button is-link">Save Form</button>
                    </div>
                  </div>

                  <div v-if="showCreateFlow">
                    <div class="field is-horizontal">
                      <div class="field-label is-normal">
                        <label class="label">Slug</label>
                      </div>
                      <div class="field-body">
                        <div class="field">
                          <div class="control">
                            <input class="input" type="text" placeholder="" v-model="form.slug">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="field is-horizontal">
                      <div class="field-label is-normal">
                        <label class="label">ID</label>
                      </div>
                      <div class="field-body">
                        <div class="field">
                          <div class="control">
                            <input class="input" type="text" placeholder="" v-model="form.id">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="field is-horizontal">
                      <div class="field-label is-normal">
                        <label class="label">Description</label>
                      </div>
                      <div class="field-body">
                        <div class="field">
                          <div class="control">
                            <input class="input" type="text" placeholder="" v-model="form.description">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div v-if="showCreateFlow">
                    <v-card class="mb-5">

                      <tabs v-if="showCreateFlow">
                        <tab class="box" v-for="(value, key, index) in form.steps" :name="key" :selected="getIndex(index)" :key="key">
                        <header>
                          <p>
                              Step @{{ key }}
                          </p>
                        </header>
                        <div class="">
                          <div class="">
                            
                            <draggable v-model="value.fields" v-on:change="changedPosition(key, value, index)" :move="checkMove">
                            <transition-group name="fields">
                            <div class="" style="width:100%; display:block;margin-top: 28px;" v-for="(fieldvalue, fieldkey, fieldindex) in value.fields" :key="fieldkey ">
                            

                            <v-expansion-panel>
                            <v-expansion-panel-content>
                            <div slot="header">@{{ fieldvalue.frontend__order }}. Edit @{{ fieldvalue.polsone__name }}</div>


                            <div class="">
                            <div class="">

                            <h1 class="title has-text-centered" v-text="fieldvalue.polsone__name"></h1>

                            <div class="field is-horizontal" v-if="showEditForm">
                              <div class="field-label is-normal">
                                <v-tooltip top>
                                  <label class="label" slot="activator">Polsone Name</label>
                                  <span>Field Name (Laurus/DB/Salesforce Etc).</span>
                                </v-tooltip>
                              </div>
                              
                              <div class="field-body">
                                <div class="select">
                                  <select v-model="fieldvalue.polsone__name" v-on:change="changedField(fieldvalue, 'polsone__name')">
                                    <option></option>
                                    <option v-for="(fovalue, fokey, foindex) in fieldlist" v-text="fovalue.fieldname"></option>
                                  </select>
                                </div>
                                
                              </div>
                              
                            </div>

                            <div class="field is-horizontal" v-if="showEditForm">
                              <div class="field-label is-normal">
                                <v-tooltip top>
                                  <label class="label" slot="activator">Polsone Type</label>
                                  <span>Automatically selected for you based on Polsone Name.</span>
                                </v-tooltip>
                              </div>
                              <div class="field-body">
                                <div class="field">
                                  <div class="control">
                                    <input class="input" type="text" placeholder="" v-model="fieldvalue.polsone__type" v-on:change="changedField(fieldvalue, 'polsone__type')" disabled="disabled">
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="field is-horizontal" v-if="showEditForm">

                              <div class="field-label is-normal">
                                <v-tooltip top>
                                  <label class="label" slot="activator">Required</label>
                                  <span>If field is to be required.</span>
                                </v-tooltip>
                              </div>
                              <div class="field-body">
                                <div class="field">
                                  <div class="control">
                                    <input type="checkbox" v-model="fieldvalue.polsone__required" v-on:change="changedField(fieldvalue, 'polsone__required')">
                                  </div>
                                </div>
                              </div>

                            </div>

                            <div class="field is-horizontal" v-if="showEditForm">

                              <div class="field-label is-normal">
                                <v-tooltip top>
                                  <label class="label" slot="activator">Sometimes</label>
                                  <span>When a field is toggled if the field is visible on the screen, it’s required. If it’s not visible on the screen (toggle hide) then it’s not sent/required.</span>
                                </v-tooltip>
                              </div>
                              <div class="field-body">
                                <div class="field">
                                  <div class="control">
                                    <input type="checkbox" v-model="fieldvalue.polsone__sometimes" v-on:change="changedField(fieldvalue, 'polsone__sometimes')">
                                  </div>
                                </div>
                              </div>

                            </div>

                            <div class="field is-horizontal" v-if="showEditForm" v-for="(validationvalue, validationkey, validationindex) in fieldvalue.polsone__validation" :key="validationindex">
  
                              <div class="field-label is-normal">
                                <v-tooltip top>
                                  <label class="label" slot="activator">Validation</label>
                                  <span>Advanced users only. Validation that the field must pass by before continuing.</span>
                                </v-tooltip>
                              </div>
                              <div class="field-body">
                                <div class="field">
                                  <div class="control">
                                    <input class="input" type="text" placeholder="" v-model="fieldvalue.polsone__validation" v-on:change="changedField(fieldvalue, 'polsone__validation')">
                                  </div>
                                </div>
                              </div>

                            </div>

                            <div class="field is-horizontal" v-if="showEditForm" style="margin-bottom:4px;">

                              <div class="field-label is-normal">
                                <v-tooltip top>
                                  <label class="label" slot="activator">Toggles</label>
                                  <span>Advanced users only. Show/Hide fields when set conditions are met.</span>
                                </v-tooltip>
                              </div>
                              <div class="field-body">
                                <div class="field">
                                  <div class="control">
                                    <v-expansion-panel>
                                      <v-expansion-panel-content>
                                        <div slot="header">Edit</div>
                                        <v-card>
                                          <v-card-text>

                                                <div v-for="(togglevalue, togglekey, toggleindex) in fieldvalue.frontend__toggles" :key="togglekey" class="field is-horizontal" style="margin-bottom:4px;" v-on:change="changedField(fieldvalue, 'frontend__toggles')">

                                                  <div class="field-label is-normal">
                                                    <label class="label">Display</label>
                                                  </div>
                                                  <div class="field-body">
                                                    <div class="field">
                                                      <div class="control">
                                                        <input class="input" type="text" placeholder="" v-model="togglevalue.display">
                                                      </div>
                                                    </div>
                                                  </div>

                                                  <div class="field-label is-normal">
                                                    <label class="label">Multiple</label>
                                                  </div>
                                                  <div class="field-body">
                                                    <div class="field">
                                                      <div class="control">
                                                        <input class="input" type="text" placeholder="" v-model="togglevalue.multiple">
                                                      </div>
                                                    </div>
                                                  </div>

                                                  <div class="field-label is-normal">
                                                    <label class="label">Criteria</label>
                                                  </div>
                                                  <div class="field-body" v-for="(criteriaValue, criteriaKey, criteriaIndex) in togglevalue.criteria">
                                                    <div class="field">
                                                      <div class="control alternating-boxes">
                                                          
                                                        <input class="input" type="text" v-model="criteriaValue.field">
                                                        <input class="input" type="text" v-model="criteriaValue.operator">
                                                        <input class="input" type="text" v-model="criteriaValue.value">

                                                      </div>
                                                    </div>
                                                  </div>

                                                  <div class="field" v-if="showEditForm" style="margin-top:18px;" @click.prevent="addToggleField(index,fieldkey)">
                                                    <div class="control">
                                                      <button class="button is-link">Add Criteria</button>
                                                    </div>
                                                  </div>

                                                </div>

                                                <div class="field" v-if="showEditForm" style="margin-top:18px;" @click.prevent="addToggle(index,fieldkey)">
                                                  <div class="control">
                                                    <button class="button is-link">Add Toggle</button>
                                                  </div>
                                                </div>

                                          </v-card-text>
                                        </v-card>
                                      </v-expansion-panel-content>
                                    </v-expansion-panel>
                                  </div>
                                </div>
                              </div>

                            </div>

                            <div class="field is-horizontal">

                              <div class="field-label is-normal">
                                <v-tooltip top>
                                  <label class="label" slot="activator">Label</label>
                                  <span>The field label.</span>
                                </v-tooltip>
                              </div>
                              <div class="field-body">
                                <div class="field">
                                  <div class="control">
                                    <input class="input" type="text" placeholder="" v-model="fieldvalue.frontend__label" v-on:change="changedField(fieldvalue, 'frontend__label')">
                                  </div>
                                </div>
                              </div>
                            </div>
                            
                            <div class="field is-horizontal" v-if="showEditForm">
                              <div class="field-label is-normal">
                                <v-tooltip top>
                                  <label class="label" slot="activator">Value</label>
                                  <span>An optional default value for the field.</span>
                                </v-tooltip>
                              </div>
                              <div class="field-body">
                                <div class="field">
                                  <div class="control">
                                    <input class="input" type="text" placeholder="" v-model="fieldvalue.frontend__value" v-on:change="changedField(fieldvalue, 'frontend__value')">
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="field is-horizontal">

                              <div class="field-label is-normal">
                                <v-tooltip top>
                                  <label class="label" slot="activator">Options</label>
                                  <span>Options for dropdowns, checkboxes, radios etc.</span>
                                </v-tooltip>
                              </div>
                              <div class="field-body">
                                <div class="field">
                                  <div class="control">
                                    <v-expansion-panel>
                                      <v-expansion-panel-content>
                                        <div slot="header">Edit</div>
                                        <v-card>
                                          <v-card-text>

                                            <draggable v-model="fieldvalue.frontend__options">
                                              <transition-group name="list-complete">
                                                <div v-for="(optionvalue, optionkey, optionindex) in fieldvalue.frontend__options" :key="optionkey" class="field is-horizontal" style="margin-bottom:4px;" v-on:change="changedField(fieldvalue, 'frontend__options')">
                                                  <div class="field-label is-normal">
                                                    <label class="label">Key</label>
                                                  </div>
                                                  <div class="field-body">
                                                    <div class="field">
                                                      <div class="control">
                                                        <input class="input" type="text" placeholder="" v-model="optionvalue.key">
                                                      </div>
                                                    </div>
                                                  </div>

                                                  <div class="field-label is-normal">
                                                    <label class="label">Text</label>
                                                  </div>
                                                  <div class="field-body">
                                                    <div class="field">
                                                      <div class="control">
                                                        <input class="input" type="text" placeholder="" v-model="optionvalue.text">
                                                      </div>
                                                    </div>
                                                  </div>

                                                </div>
                                              </transition-group>
                                            </draggable>

                                            <div class="field" style="margin-top:18px;" @click.prevent="addOption(index,fieldkey)">
                                              <div class="control">
                                                <button class="button is-link">Add Option</button>
                                              </div>
                                            </div>

                                          </v-card-text>
                                        </v-card>
                                      </v-expansion-panel-content>
                                    </v-expansion-panel>
                                  </div>
                                </div>
                              </div>

                            </div>

                            <div class="field is-horizontal">

                              <div class="field-label is-normal">
                                <v-tooltip top>
                                  <label class="label" slot="activator">Placeholder</label>
                                  <span>A placeholder value for the field.</span>
                                </v-tooltip>
                              </div>
                              <div class="field-body">
                                <div class="field">
                                  <div class="control">
                                    <input class="input" type="text" placeholder="" v-model="fieldvalue.frontend__placeholder" v-on:change="changedField(fieldvalue, 'frontend__placeholder')">
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="field is-horizontal">

                              <div class="field-label is-normal">
                                <v-tooltip top>
                                  <label class="label" slot="activator">Help Text</label>
                                  <span>Help text for the field.</span>
                                </v-tooltip>
                              </div>
                              <div class="field-body">
                                <div class="field">
                                  <div class="control">
                                    <input class="input" type="text" placeholder="" v-model="fieldvalue.frontend__help_text" v-on:change="changedField(fieldvalue, 'frontend__help_text')">
                                  </div>
                                </div>
                              </div>

                            </div>

                            <div class="field is-horizontal">

                              <div class="field-label is-normal">
                                <v-tooltip top>
                                  <label class="label" slot="activator">Heading</label>
                                  <span>Field heading.</span>
                                </v-tooltip>
                              </div>
                              <div class="field-body">
                                <div class="field">
                                  <div class="control">
                                    <input class="input" type="text" placeholder="" v-model="fieldvalue.frontend__heading" v-on:change="changedField(fieldvalue, 'frontend__heading')">
                                  </div>
                                </div>
                              </div>

                            </div>

                            <div class="field is-horizontal">

                              <div class="field-label is-normal">
                                <v-tooltip top>
                                  <label class="label" slot="activator">Section Heading</label>
                                  <span>A section heading that will come before the field</span>
                                </v-tooltip>
                              </div>
                              <div class="field-body">
                                <div class="field">
                                  <div class="control">
                                    <input class="input" type="text" placeholder="" v-model="fieldvalue.frontend__section" v-on:change="changedField(fieldvalue, 'frontend__section')">
                                  </div>
                                </div>
                              </div>

                            </div>

                            <div class="field is-horizontal" v-if="showEditForm">

                              <div class="field-label is-normal">
                                <v-tooltip top>
                                  <label class="label" slot="activator">Hidden</label>
                                  <span>Set the field as a hidden field.</span>
                                </v-tooltip>
                              </div>
                              <div class="field-body">
                                <div class="field">
                                  <div class="control">
                                    <input type="checkbox" v-model="fieldvalue.frontend__hidden" v-on:change="changedField(fieldvalue, 'frontend__hidden')">
                                  </div>
                                </div>
                              </div>

                            </div>

                            <div class="field is-horizontal" v-if="showEditForm">

                              <div class="field-label is-normal">
                                <v-tooltip top>
                                  <label class="label" slot="activator">Choices</label>
                                  <span>Fancy select. Timezone Etc.</span>
                                </v-tooltip>
                              </div>
                              <div class="field-body">
                                <div class="field">
                                  <div class="control">
                                    <input type="checkbox" v-model="fieldvalue.frontend__choices" v-on:change="changedField(fieldvalue, 'frontend__choices')">
                                  </div>
                                </div>
                              </div>

                            </div>

                            <div class="field is-horizontal" v-if="showEditForm">

                              <div class="field-label is-normal">
                                <v-tooltip top>
                                  <label class="label" slot="activator">Choices Locked</label>
                                  <span>Lock the choices.</span>
                                </v-tooltip>
                              </div>
                              <div class="field-body">
                                <div class="field">
                                  <div class="control">
                                    <input type="checkbox" v-model="fieldvalue.frontend__choices_locked" v-on:change="changedField(fieldvalue, 'frontend__choices_locked')">
                                  </div>
                                </div>
                              </div>

                            </div>


                            <div class="field is-horizontal" v-if="showEditForm">

                              <div class="field-label is-normal">
                                <v-tooltip top>
                                  <label class="label" slot="activator">Choices Allow Multiple</label>
                                  <span>Allow multiple choices.</span>
                                </v-tooltip>
                              </div>
                              <div class="field-body">
                                <div class="field">
                                  <div class="control">
                                    <input type="checkbox" v-model="fieldvalue.frontend__choices_allow_multiple" v-on:change="changedField(fieldvalue, 'frontend__choices_allow_multiple')">
                                  </div>
                                </div>
                              </div>

                            </div>

                            <div class="field is-horizontal" style="display:none;">

                              <div class="field-label is-normal">
                                <label class="label">Frontend Order</label>
                              </div>
                              <div class="field-body">
                                <div class="field">
                                  <div class="control">
                                    <input class="input" type="text" placeholder="" v-model="fieldvalue.frontend__order" v-on:change="changedField(fieldvalue, 'frontend__order')">
                                  </div>
                                </div>
                              </div>

                            </div>
                            
                            </div>
                            </div>


                            </v-expansion-panel-content>
                            </v-expansion-panel>


                            </div>
                            
                            </transition-group>
                            </draggable>


                            <div class="field" style="margin-top:18px;margin-bottom:18px;" @click.prevent="addField(index, value)">
                              <div class="control">
                                <button class="button is-link">Add Field</button>
                              </div>
                            </div>





                            </div>

                          </div>


                        </div>
                        
                        </tab>
                      </tabs>

                      <tabs v-if="showEditFlow">
                        <tab class="box" v-for="(value, key, index) in formV.steps" :name="key" :selected="getIndex(index)" :key="key">
                        <header>
                          <p>
                              Step @{{ key }}
                          </p>
                        </header>
                        <div class="">
                          <div class="">
                            
                            <draggable v-model="value.fields" v-on:change="changedPositionEditFlow(key, value, index)" :move="checkMove">
                            <transition-group name="fields">
                            <div class="" style="width:100%; display:block;margin-top: 28px;" v-for="(fieldvalue, fieldkey, fieldindex) in value.fields" :key="fieldkey ">
                            

                            <v-expansion-panel>
                            <v-expansion-panel-content>
                            <div slot="header">@{{ fieldvalue.frontend__order }}. Edit @{{ fieldvalue.polsone__name }}</div>


                            <div class="">
                            <div class="">

                            <h1 class="title has-text-centered" v-text="fieldvalue.polsone__name"></h1>

                            <div class="field is-horizontal" v-if="showEditForm">
                              <div class="field-label is-normal">
                                <v-tooltip top>
                                  <label class="label" slot="activator">Polsone Name</label>
                                  <span>Field Name (Laurus/DB/Salesforce Etc).</span>
                                </v-tooltip>
                                
                              </div>

                              <div class="field-body">
                                <div class="select">
                                  <select v-model="fieldvalue.polsone__name" v-on:change="changedField(fieldvalue, 'polsone__name')">
                                    <option></option>
                                    <option v-for="(fovalue, fokey, foindex) in fieldlist" v-text="fovalue.fieldname"></option>
                                  </select>
                                </div>
                              </div>

                            </div>

                            <div class="field is-horizontal" v-if="showEditForm">
                              <div class="field-label is-normal">
                                <v-tooltip top>
                                  <label class="label" slot="activator">Polsone Type</label>
                                  <span>Automatically selected for you based on Polsone Name.</span>
                                </v-tooltip>
                              </div>
                              <div class="field-body">
                                <div class="field">
                                  <div class="control">
                                    <input class="input" type="text" placeholder="" v-model="fieldvalue.polsone__type" v-on:change="changedField(fieldvalue, 'polsone__type')" disabled="disabled">
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="field is-horizontal" v-if="showEditForm">

                              <div class="field-label is-normal">
                                <v-tooltip top>
                                  <label class="label" slot="activator">Required</label>
                                  <span>Check if field is to be required.</span>
                                </v-tooltip>
                                
                              </div>
                              <div class="field-body">
                                <div class="field">
                                  <div class="control">
                                    <input type="checkbox" v-model="fieldvalue.polsone__required" v-on:change="changedField(fieldvalue, 'polsone__required')">
                                  </div>
                                </div>
                              </div>

                            </div>

                            <div class="field is-horizontal" v-if="showEditForm">

                              <div class="field-label is-normal">
                                <v-tooltip top>
                                  <label class="label" slot="activator">Sometimes</label>
                                  <span>When a field is toggled if the field is visible on the screen, it’s required. If it’s not visible on the screen (toggle hide) then it’s not sent/required.</span>
                                </v-tooltip>
                                
                              </div>
                              <div class="field-body">
                                <div class="field">
                                  <div class="control">
                                    <input type="checkbox" v-model="fieldvalue.polsone__sometimes" v-on:change="changedField(fieldvalue, 'polsone__sometimes')">
                                  </div>
                                </div>
                              </div>

                            </div>

                            <div class="field is-horizontal" v-if="showEditForm" v-for="(validationvalue, validationkey, validationindex) in fieldvalue.polsone__validation" :key="validationindex">
  
                              <div class="field-label is-normal">
                                <v-tooltip top>
                                  <label class="label" slot="activator">Validation</label>
                                  <span>Advanced users only. Validation that the field must pass by before continuing.</span>
                                </v-tooltip>
                                
                              </div>
                              <div class="field-body">
                                <div class="field">
                                  <div class="control">
                                    <input class="input" type="text" placeholder="" v-model="fieldvalue.polsone__validation" v-on:change="changedField(fieldvalue, 'polsone__validation')">
                                  </div>
                                </div>
                              </div>

                            </div>





                            <div class="field is-horizontal" v-if="showEditForm" style="margin-bottom:4px;">

                              <div class="field-label is-normal">
                                <v-tooltip top>
                                  <label class="label" slot="activator">Toggles</label>
                                  <span>Advanced users only. Show/Hide fields when set conditions are met.</span>
                                </v-tooltip>
                                
                              </div>
                              <div class="field-body">
                                <div class="field">
                                  <div class="control">
                                    <v-expansion-panel>
                                      <v-expansion-panel-content>
                                        <div slot="header">Edit</div>
                                        <v-card>
                                          <v-card-text>

                                                <div v-for="(togglevalue, togglekey, toggleindex) in fieldvalue.frontend__toggles" :key="togglekey" class="field is-horizontal" style="margin-bottom:4px;" v-on:change="changedField(fieldvalue, 'frontend__toggles')">


                                                  <div class="field-label is-normal">
                                                    <label class="label">Display</label>
                                                  </div>
                                                  <div class="field-body">
                                                    <div class="field">
                                                      <div class="control">
                                                        <input class="input" type="text" placeholder="" v-model="togglevalue.display">
                                                      </div>
                                                    </div>
                                                  </div>

                                                  <div class="field-label is-normal">
                                                    <label class="label">Multiple</label>
                                                  </div>
                                                  <div class="field-body">
                                                    <div class="field">
                                                      <div class="control">
                                                        <input class="input" type="text" placeholder="" v-model="togglevalue.multiple">
                                                      </div>
                                                    </div>
                                                  </div>

                                                  <div class="field-label is-normal">
                                                    <label class="label">Criteria</label>
                                                  </div>
                                                  <div class="field-body">
                                                    <div class="field">
                                                      <div class="control" v-for="(tovalue, tokey, toindex) in togglevalue.criteria" :key="tokey">
                                                        <input class="input" type="text" v-model="togglevalue.criteria['field']">
                                                        <input class="input" type="text" v-model="togglevalue.criteria['operator']">
                                                        <input class="input" type="text" v-model="togglevalue.criteria['value']">
                                                      </div>
                                                    </div>
                                                  </div>

                                                  <div class="field" v-if="showEditForm" style="margin-top:18px;" @click.prevent="addToggleFieldEditFlow(index,fieldkey)">
                                                    <div class="control">
                                                      <button class="button is-link">Add Criteria</button>
                                                    </div>
                                                  </div>

                                                </div>

                                                <div class="field" v-if="showEditForm" style="margin-top:18px;" @click.prevent="addToggleEditFlow(index,fieldkey)">
                                                  <div class="control">
                                                    <button class="button is-link">Add Toggle</button>
                                                  </div>
                                                </div>






                                          </v-card-text>
                                        </v-card>
                                      </v-expansion-panel-content>
                                    </v-expansion-panel>
                                  </div>
                                </div>
                              </div>

                            </div>

                            <div class="field is-horizontal">

                              <div class="field-label is-normal">
                                <v-tooltip top>
                                  <label class="label" slot="activator">Label</label>
                                  <span>The field label.</span>
                                </v-tooltip>
                                
                              </div>
                              <div class="field-body">
                                <div class="field">
                                  <div class="control">
                                    <input class="input" type="text" placeholder="" v-model="fieldvalue.frontend__label" v-on:change="changedField(fieldvalue, 'frontend__label')">
                                  </div>
                                </div>
                              </div>
                            </div>
                            
                            <div class="field is-horizontal" v-if="showEditForm">
                              <div class="field-label is-normal">
                                <v-tooltip top>
                                  <label class="label" slot="activator">Value</label>
                                  <span>An optional default value for the field.</span>
                                </v-tooltip>
                                
                              </div>
                              <div class="field-body">
                                <div class="field">
                                  <div class="control">
                                    <input class="input" type="text" placeholder="" v-model="fieldvalue.frontend__value" v-on:change="changedField(fieldvalue, 'frontend__value')">
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="field is-horizontal">

                              <div class="field-label is-normal">
                                <v-tooltip top>
                                  <label class="label" slot="activator">Options</label>
                                  <span>Options for dropdowns, checkboxes, radios etc.</span>
                                </v-tooltip>
                                
                              </div>
                              <div class="field-body">
                                <div class="field">
                                  <div class="control">
                                    <v-expansion-panel>
                                      <v-expansion-panel-content>
                                        <div slot="header">Edit</div>
                                        <v-card>
                                          <v-card-text>

                                            <draggable v-model="fieldvalue.frontend__options">
                                              <transition-group name="list-complete">
                                                <div v-for="(optionvalue, optionkey, optionindex) in fieldvalue.frontend__options" :key="optionkey" class="field is-horizontal" style="margin-bottom:4px;" v-on:change="changedField(fieldvalue, 'frontend__options')">
                                                  <div class="field-label is-normal">
                                                    <label class="label">Key</label>
                                                  </div>
                                                  <div class="field-body">
                                                    <div class="field">
                                                      <div class="control">
                                                        <input class="input" type="text" placeholder="" v-model="optionvalue.key">
                                                      </div>
                                                    </div>
                                                  </div>

                                                  <div class="field-label is-normal">
                                                    <label class="label">Text</label>
                                                  </div>
                                                  <div class="field-body">
                                                    <div class="field">
                                                      <div class="control">
                                                        <input class="input" type="text" placeholder="" v-model="optionvalue.text">
                                                      </div>
                                                    </div>
                                                  </div>

                                                  

                                                </div>
                                              </transition-group>
                                            </draggable>

                                            <div class="field" style="margin-top:18px;" @click.prevent="addOptionEditFlow(index,fieldkey)">
                                              <div class="control">
                                                <button class="button is-link">Add Option</button>
                                              </div>
                                            </div>

                                          </v-card-text>
                                        </v-card>
                                      </v-expansion-panel-content>
                                    </v-expansion-panel>
                                  </div>
                                </div>
                              </div>

                            </div>

                            <div class="field is-horizontal">

                              <div class="field-label is-normal">
                                <v-tooltip top>
                                  <label class="label" slot="activator">Placeholder</label>
                                  <span>A placeholder value for the field.</span>
                                </v-tooltip>
                                
                              </div>
                              <div class="field-body">
                                <div class="field">
                                  <div class="control">
                                    <input class="input" type="text" placeholder="" v-model="fieldvalue.frontend__placeholder" v-on:change="changedField(fieldvalue, 'frontend__placeholder')">
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="field is-horizontal">

                              <div class="field-label is-normal">
                                <v-tooltip top>
                                  <label class="label" slot="activator">Help Text</label>
                                  <span>Help text for the field.</span>
                                </v-tooltip>
                                
                              </div>
                              <div class="field-body">
                                <div class="field">
                                  <div class="control">
                                    <input class="input" type="text" placeholder="" v-model="fieldvalue.frontend__help_text" v-on:change="changedField(fieldvalue, 'frontend__help_text')">
                                  </div>
                                </div>
                              </div>

                            </div>

                            <div class="field is-horizontal">

                              <div class="field-label is-normal">
                                <v-tooltip top>
                                  <label class="label" slot="activator">Heading</label>
                                  <span>Field heading.</span>
                                </v-tooltip>
                                
                              </div>
                              <div class="field-body">
                                <div class="field">
                                  <div class="control">
                                    <input class="input" type="text" placeholder="" v-model="fieldvalue.frontend__heading" v-on:change="changedField(fieldvalue, 'frontend__heading')">
                                  </div>
                                </div>
                              </div>

                            </div>

                            <div class="field is-horizontal">

                              <div class="field-label is-normal">
                                <v-tooltip top>
                                  <label class="label" slot="activator">Section Heading</label>
                                  <span>A section heading that will come before the field</span>
                                </v-tooltip>
                                
                              </div>
                              <div class="field-body">
                                <div class="field">
                                  <div class="control">
                                    <input class="input" type="text" placeholder="" v-model="fieldvalue.frontend__section" v-on:change="changedField(fieldvalue, 'frontend__section')">
                                  </div>
                                </div>
                              </div>

                            </div>

                            <div class="field is-horizontal" v-if="showEditForm">

                              <div class="field-label is-normal">
                                <v-tooltip top>
                                  <label class="label" slot="activator">Hidden</label>
                                  <span>Set the field as a hidden field.</span>
                                </v-tooltip>
                                
                              </div>
                              <div class="field-body">
                                <div class="field">
                                  <div class="control">
                                    <input type="checkbox" v-model="fieldvalue.frontend__hidden" v-on:change="changedField(fieldvalue, 'frontend__hidden')">
                                  </div>
                                </div>
                              </div>

                            </div>

                            <div class="field is-horizontal" v-if="showEditForm">

                              <div class="field-label is-normal">
                                <v-tooltip top>
                                  <label class="label" slot="activator">Choices</label>
                                  <span>Fancy select. Timezone Etc.</span>
                                </v-tooltip>
                                
                              </div>
                              <div class="field-body">
                                <div class="field">
                                  <div class="control">
                                    <input type="checkbox" v-model="fieldvalue.frontend__choices" v-on:change="changedField(fieldvalue, 'frontend__choices')">
                                  </div>
                                </div>
                              </div>

                            </div>

                            <div class="field is-horizontal" v-if="showEditForm">

                              <div class="field-label is-normal">
                                <v-tooltip top>
                                  <label class="label" slot="activator">Choices Locked</label>
                                  <span>Lock the choices.</span>
                                </v-tooltip>
                                
                              </div>
                              <div class="field-body">
                                <div class="field">
                                  <div class="control">
                                    <input type="checkbox" v-model="fieldvalue.frontend__choices_locked" v-on:change="changedField(fieldvalue, 'frontend__choices_locked')">
                                  </div>
                                </div>
                              </div>

                            </div>

                            <div class="field is-horizontal" v-if="showEditForm">

                              <div class="field-label is-normal">
                                <v-tooltip top>
                                  <label class="label" slot="activator">Choices Allow Multiple</label>
                                  <span>Allow multiple choices.</span>
                                </v-tooltip>
                                
                              </div>
                              <div class="field-body">
                                <div class="field">
                                  <div class="control">
                                    <input type="checkbox" v-model="fieldvalue.frontend__choices_allow_multiple" v-on:change="changedField(fieldvalue, 'frontend__choices_allow_multiple')">
                                  </div>
                                </div>
                              </div>

                            </div>

                            <div class="field is-horizontal" style="display:none;">

                              <div class="field-label is-normal">
                                <label class="label">Frontend Order</label>
                              </div>
                              <div class="field-body">
                                <div class="field">
                                  <div class="control">
                                    <input class="input" type="text" placeholder="" v-model="fieldvalue.frontend__order" v-on:change="changedField(fieldvalue, 'frontend__order')">
                                  </div>
                                </div>
                              </div>

                            </div>

                            

                            

                            
                            </div>
                            </div>


                            </v-expansion-panel-content>
                            </v-expansion-panel>


                            </div>
                            
                            </transition-group>
                            </draggable>


                            <div class="field" style="margin-top:18px;margin-bottom:18px;" @click.prevent="addFieldEditFlow(index, value)">
                              <div class="control">
                                <button class="button is-link">Add Field</button>
                              </div>
                            </div>





                            </div>

                          </div>


                        </div>
                        
                        </tab>
                      </tabs>

                    </v-card>
                  </div>
                  
                </v-stepper-content>
              </v-stepper-items>
            </v-stepper>
          </v-app>
        </div>

 
        <!-- CDNJS :: Sortable (https://cdnjs.com/) -->
        <script src="//cdn.jsdelivr.net/npm/sortablejs@1.7.0/Sortable.min.js"></script>
  
        <!-- CDNJS :: Vue.Draggable (https://cdnjs.com/) -->
        <script src="//cdnjs.cloudflare.com/ajax/libs/Vue.Draggable/2.16.0/vuedraggable.min.js"></script>

        <script src="/js/app.js"></script>

    </body>
</html>
