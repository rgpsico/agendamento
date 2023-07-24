<x-text-input name="nome" size="30" label="Nome Completo" :value="$model"/>
<x-text-input name="email" size="30" label="Email" :value="$model"/>
<label for="">Data de Nacimento</label>
<input type="text" name="data_nascimento" class='form-control mb-2' id="data_nascimento" value="{{ \Carbon\Carbon::parse($model->data_nascimento)->format('d/m/Y') }}
"/>
<x-text-input name="telefone" size="30" label="Telefone" :value="$model"/>

                                            </div>
                                                           