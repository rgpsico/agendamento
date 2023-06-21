<input type="hidden" class="" name="empresa_id" value="{{Auth::user()->id}}" />                                                              
												
												
												<img class=""  src="{{ asset('servico/' . $model->imagem ?? '') }}" width="100px" height="100px" alt="Imagem do serviço">

												<div class="form-group">
													<label for="">Imagem da Aula</label>
													<input type="file" name="imagem">
												</div>

                                                <x-text-input name="titulo" size="30" label="Titulo" :value="$model ?? ''"/>
                        
                                                <x-text-area name="descricao" label="Descrição" :model="$model ?? ''" />                                                                                       
                                                
                                                <x-text-input name="preco" size="30" label="Preço" :value="$model ?? '' ?? ''" />
                        
                                                <x-text-input name="tempo_de_aula" size="30" label="Tempo de Aula" :value="$model ?? '' ?? ''" />
                                                
                                            
                                            </div>
                                                           