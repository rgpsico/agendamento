<input type="text" class="" name="empresa_id" value="{{Auth::user()->id}}" />                                                              
<x-text-input name="aluno_id" size="30" label="aluno_id" :value="$model ?? ''"/>
<x-text-area name="modalidade_id" label="Modalidade" :model="$model ?? ''" />                                                                                       
<input type="text" name="professor_id" value="{{Auth::user()->professor->id}}">
<x-text-input name="data_da_aula" size="30" label="Data da Aula" :value="$model ?? '' ?? ''" />
<x-text-input name="valor_aula" size="30" label="Valor da Aula" :value="$model ?? '' ?? ''" />
</div>
                                                           