<?php

namespace Database\Seeders;

use App\Models\Block;
use App\Models\Level;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear los 6 niveles jerárquicos
        $level1 = Level::create(['stage' => 1, 'name' => 'EJE TEMÁTICO']);
        $level2 = Level::create(['stage' => 2, 'name' => 'COMPONENTES']);
        $level3 = Level::create(['stage' => 3, 'name' => 'TEMA']);
        $level4 = Level::create(['stage' => 4, 'name' => 'SUBTEMA']);
        $level5 = Level::create(['stage' => 5, 'name' => 'SUBSUBTEMA']);
        $level6 = Level::create(['stage' => 6, 'name' => 'SUBSUBSUBTEMA']);

        // Estructura de datos del CSV
        $estructura = [
            'APTITUD ACADÉMICA' => [
                'RAZONAMIENTO LÓGICO' => [
                    '¿QUÉ ES LÓGICA?' => [
                        'Definición de lógica' => [],
                        'Importancia' => [],
                        'Términos lógicos' => [
                            'Proposición',
                            'Premisa',
                            'Conclusión',
                            'Inferencia',
                            'Verdad',
                            'Validez'
                        ]
                    ],
                    'INFERENCIAS INMEDIATAS' => [
                        'Inferencias inmediatas. Definición' => [],
                        'Inferencias por observación' => [
                            'Conversión',
                            'Contraposición'
                        ]
                    ],
                    'ARGUMENTOS' => [
                        'Agumentos. Definición' => [],
                        'Argumentos. Tipos' => [
                            'Deductivo',
                            'Inductivo',
                            'Abductivo'
                        ]
                    ],
                    'LÓGICA PROPOSICIONAL' => [
                        'Clasificación y tipos de proposiciones' => [
                            'Conjuntiva',
                            'Disyuntiva',
                            'Disyuntiva inclusiva',
                            'Disyuntiva exclusiva',
                            'Condicional',
                            'Bicondicional',
                            'Negación'
                        ],
                        'Formalización de proposiciones, variables y operadores.' => []
                    ],
                    'FÓRMULAS PROPOSICIONALES' => [
                        'Tablas de verdad.' => [
                            'Conjunción',
                            'Disyunción inclusiva',
                            'Disyunción exclusiva',
                            'Condicional',
                            'Bicondicional',
                            'Negación'
                        ]
                    ],
                    'ANÁLISIS DE LA VALIDEZ DE FÓRMULAS' => [
                        'El método abreviado' => [],
                        'Análisis de la validez. Reglas' => []
                    ],
                    'CUADRO TRADICIONAL DE OPOSICIÓN' => [
                        'Contradictorias' => [],
                        'Contrarias' => [],
                        'Subcontrarias' => [],
                        'Subalternas' => []
                    ],
                    'LÓGICA DE CLASES' => [
                        'Relación entre clases' => [],
                        'Conexiones entre clases' => []
                    ],
                    'FORMULACIONES Y DIAGRAMAS' => [
                        'Diagrama de Venn' => [],
                        'Diagramación de proposiciones categóricas típicas.' => [],
                        'Diagramación de proposiciones categóricas atípicas.' => []
                    ],
                    'SILOGISMO CATEGÓRICO' => [
                        'Silogismo.Características' => [],
                        'Silogismo. Figuras y modos' => []
                    ]
                ],
                'RAZONAMIENTO MATEMÁTICO' => [
                    'RAZONAMIENTO NUMÉRICO' => [
                        'Problemas usando las cuatro operaciones' => [],
                        'Progresiones' => [
                            'Pogresiones Aritméticas',
                            'Progresiones Geométricas'
                        ],
                        'Criptoaritmética' => [],
                        'Planteo y solución de ecuaciones e inecuaciones' => [],
                        'Edades' => [],
                        'Fracciones y porcentajes' => [],
                        'Máximo común divisor' => [],
                        'Mínimo común múltiplo' => [],
                        'conjuntos y operaciones sobre conjuntos' => [],
                        'Factorial de un número' => [],
                        'Técnicas de conteo' => [
                            'Variación',
                            'Permutación',
                            'Combinación'
                        ],
                        'Probabilidades' => [
                            'Simple',
                            'Condicional'
                        ]
                    ],
                    'MAGNITUDES PROPORCIONALES' => [
                        'Magnitudes directas e inversas' => [],
                        'Razones y propociones' => [],
                        'Tanto por ciento, mezclas' => [],
                        'Reparto proporcional' => [],
                        'Media' => [
                            'Media aritmética',
                            'Media geometrica',
                            'Media armónica'
                        ],
                        'Regla de tres' => [
                            'Simple (directa e inversa)',
                            'Compuesta (directa e inversa)'
                        ]
                    ],
                    'OPERADORES' => [
                        'Operadores no binarios' => [],
                        'Operadores binarios (utilizando operaciones básicas)' => [],
                        'Operadores definidos por tablas' => []
                    ],
                    'TABLAS Y GRÁFICOS ESTADÍSTICOS' => [
                        'Variables cualitativas y cuantitativas' => [],
                        'Gráficos estadísticos' => [
                            'Gráficos circulares',
                            'Barras',
                            'Líneas'
                        ],
                        'Tabla de frecuencias' => [],
                        'Medidas de tendencia central' => [],
                        'Medidas de dispersión.' => []
                    ]
                ],
                'COMPRENSIÓN LECTORA' => [
                   /* 'COMPRENSIÓN DE LECTURA' => [
                        'Comprensión de lectura. Definición' => [],
                        'Estructura y composición de las palabras' => [],
                        'Raíces y prefijos griegos' => [],
                        'Raíces y prefijos latinos' => [],
                        'Locuciones latinas' => [],
                        'Definición de texto' => [],
                        'Estructura interna' => [],
                        'Estructura externa' => []
                    ],
                    'LECTURA' => [
                        'Definición de lectura' => [],
                        'Proceso lector' => [],
                        'Estrategias de lectura' => [
                            'el subrayado',
                            'el sumillado'
                        ],
                        'Niveles de comprensión de lectura' => [
                            'Nivel literal',
                            'Nivel inferencial',
                            'Nivel crítico'
                        ],
                        'Tipos de preguntas' => [
                            'Por su generalidad',
                            'Por su particularidad',
                            'Preguntas de afirmación',
                            'Preguntas de negación.'
                        ]
                    ],
                    'TEXTO' => [
                        'Texto. Definición' => [],
                        'Tipologia de textos' => [
                            'texto expositivo',
                            'texto narrativo',
                            'instructivo',
                            'texto argumentativo',
                            'transaccional'
                        ],
                        'Otras clasificaciones según su formato' => [
                            'continuo',
                            'discontinuo',
                            'mixto'
                        ],
                        'Por su contenido' => [
                            'textos científicos',
                            'textos humanísticos'
                        ],
                        'Mecanismos de referencia' => [],
                        'Los referentes textuales' => [],
                        'Referencias endofóricas textuales' => [
                            'anáfora',
                            'catáfora',
                            'elipsis',
                            'sustitución léxica'
                        ],
                        'Referencias exofóricas' => ['Deixis'],
                        'Textos múltiples' => [
                            'Textos Múltiples',
                            'Perspectivas Divergentes'
                        ],
                        'Intertextualidad' => [],
                        'Contraste de perspectivas' => [],
                        'Construcción del sentido colaborativo' => []
                    ],
                    'MARCADORES Y CONECTORES LÓGICOS' => [
                        'Marcadores y conectores loʻgicos. Definición' => [],
                        'Clasificación' => [
                            'Opositivos',
                            'Consecutivos',
                            'Causales',
                            'Condicionales',
                            'Aditivos',
                            'Bifurcación',
                            'Comparativos',
                            'Reformulativos',
                            'De orden',
                            'De finalidad',
                            'De énfasis',
                            'De evidencia'
                        ],
                        'Casos especiales' => [
                            'porqué / por qué porque / por que',
                            'conque/con qué / conqué',
                            'i no / sino',
                            'asi mismo/asimismo / a si mismo',
                            'pues. / por cierto',
                            'dicho sea de paso',
                            'a todo esto',
                            'a propósito de',
                            'así las cosas',
                            'en vista de ello',
                            'dicho esto',
                            'pues bien/es decir',
                            'o sea',
                            'esto es, a saber / ahora bien',
                            'al contrario',
                            'por el contrario',
                            'no obstante',
                            'sin embargo',
                            'con todo',
                            'en cambio',
                            'empero',
                            'a pesar de, eso sí',
                            'antes bien'
                        ],
                        'Relación lógica de ideas. Tipos' => [
                            'Causa-consecuencia',
                            'ejemplificación',
                            'comparación (semejanza-oposición)',
                            'analogía',
                            'cronologia',
                            'problema-solución.'
                        ]
                    ]
                */],
                'RAZONAMIENTO VERBAL' => [
                    'SINONÍMIA CONTEXTUAL DENOTATIVA' => [
                        'Sinonímia contextual. Definición' => [],
                        'Principios de denotación' => [],
                        'Sinonimia directa o absoluta' => [],
                        'Sinonimia indirecta o parcial' => [],
                        'Estructura del ejercicio' => [],
                        'Método de solución' => []
                    ],
                    'TÉRMINO EXCLUIDO' => [
                        'Término excluido. Definición' => [],
                        'Criterios de exclusión' => [
                            'Idea contenida en otras',
                            'causalidad',
                            'todo-parte',
                            'ejemplo o aplicación específica',
                            'jerarquía o intensidad',
                            'sinonimia y antonimia',
                            'afinidad semántica',
                            'género-especie',
                            'cogeneridad',
                            'relación múltiple',
                            'con dos campos léxicos'
                        ],
                        'Término excluido. Método de solución' => []
                    ],
                    'ANALOGÍAS' => [
                        'Analogías. Definición' => [],
                        'Principios analógicos' => [],
                        'Formas analógicas' => [
                            'horizontales',
                            'verticales'
                        ],
                        'Tipos analógicos' => [
                            'sinonimia',
                            'antonimia',
                            'parte-todo',
                            'conjunto- elemento',
                            'característica',
                            'intensidad',
                            'causalidad',
                            'evolución o temporalidad',
                            'especie-género',
                            'cogeneridad',
                            'materia prima-producto elaborado',
                            'simbolismo',
                            'contigüidad',
                            'disciplina o profesional a objeto de estudio',
                            'asociados por el lugar',
                            'función',
                            'sujeto - instrumento',
                            'complementariedad',
                            'continente- contenido',
                            'autor-obra'
                        ],
                        'Analogías. Método de solución' => []
                    ],
                    'ORACIONES INCOMPLETAS' => [
                        'Oraciones incompletas. Definición' => [],
                        'Tipos de oraciones incompletas' => [
                            'oraciones que implican análisis de categorías gramaticales',
                            'oraciones que implican concordancia gramatical',
                            'oraciones que implican análisis de lenguaje literario y/o plano connotativo',
                            'oraciones que implican conocimientos temáticos'
                        ],
                        'Oraciones incompletas. Método de solución' => []
                    ],
                    'SINONÍMIA CONTEXTUAL CONNOTATIVA' => [
                        'Sinonímia Contextual connotativa. Definición' => [],
                        'Sinonímia contextual. Connotación' => [],
                        'Sinonímia contextual. Denotación' => [],
                        'Recursos connotativos' => [
                            'Símil o comparación',
                            'metáfora',
                            'metonimia',
                            'sinécdoque',
                            'eufemismo',
                            'compuestos pluriverbales',
                            'sintagmas frásicos',
                            'implicaturas conversacionales',
                            'derivación de sociolectos'
                        ],
                        'Sinonímia contextual connotativa. Método de solución' => []
                    ],
                    'ΑΝΤΩΝΥΜΙΑ CONTEXTUAL CONNOTATIVA' => [
                        'Antonimia contextual connotativa. Definición' => [],
                        'Antonimia contextual connotativa.. Estructura del ejercicio' => [],
                        'Antonimia contextual connotativa. Método de solución' => []
                    ],
                    '1INFORMACIÓN ELIMINADA' => [
                        'Información eliminada. Definición' => [],
                        'Criterios de eliminación' => [],
                        'Redundancia (Explícita. e Implicita)' => [],
                        'Contradicción' => [],
                        'Impertinencia' => [],
                        'Información eliminada.Método de solución' => []
                    ],
                    '1REORDEΝΑΜΙΕΝΤΟ TEXTUAL.' => [
                        'Reordenamiento textual. Definición' => [],
                        'Reordenamiento textual. Estructura del ejercicio' => [],
                        'Modalidades' => [
                            'Palabras',
                            'Frases',
                            'Oraciones'
                        ],
                        'Criterios' => [
                            'Orden cronológico',
                            'Proceso',
                            'Casualidad',
                            'Clase',
                            'Espacio',
                            'Científico- académico',
                            'Escala de subjetividad'
                        ],
                        'Problema- solución' => [],
                        'Reordenamiento Textual. Método de solución' => []
                    ],
                    '1INCLUSIÓN DE ENUNCIADOS' => [
                        'Inclusion de enunciados. Definición' => [],
                        'Inclusión de enunciados. Estructura del ejercicio' => [],
                        'Criterios' => [
                            'Inclusión tipo introducción',
                            'Inclusión tipo desarrollo',
                            'Inclusión tipo cierre'
                        ],
                        'Inclusión de enunciados. Método de solución' => []
                    ]
                ]
            ],
            'MATEMÁTICA' => [
                'ARITMÉTICA' => [
                    'NÚMEROS NATURALES Y ENTEROS' => [
                        'Divisibilidad' => [],
                        'Números primos y compuestos' => []
                    ],
                    'NÚMEROS RACIONALES E IRRACIONALES' => [
                        'Fracciones' => [
                            'Fracciones ordinarias',
                            'Fracciones decimales'
                        ],
                        'Generatriz de una expresión decimal' => [],
                        'Números irracionales y representación decimal' => []
                    ],
                    'POTENCIACIÓN Y RADICACIÓN' => [
                        'Potenciación y radicación. Definición' => [],
                        'Potenciación y radicacón. Propiedades' => [],
                        'Cuadrado y cubo perfecto' => [],
                        'Raíz cuadrada' => []
                    ],
                    'INTERES SIMPLE' => [
                        'Monto generado a interés simple' => []
                    ]
                ],
                'ALGEBRA' => [
                    'POLINOMIOS' => [
                        'Operaciones con polinomios' => [],
                        'Productos y cocientes notables' => [],
                        'Racionalización de expresiones algebraicas' => [],
                        'Algoritmo de la división' => [],
                        'Radicación' => [],
                        'Máximo común divisor MCD' => [],
                        'Mínimo común múltiplo MCM de polinomios' => [],
                        'Raíces de una ecuación polinomial' => []
                    ],
                    'FACTORIZACIÓN' => [
                        'Métodos de factorización' => [
                            'factor común',
                            'agrupación de términos',
                            'identidades',
                            'aspa simple',
                            'aspa doble',
                            'divisores binómicos y artificios'
                        ]
                    ],
                    'MATRICES' => [
                        'operaciones con matrices' => [
                            'Suma',
                            'producto por un escalar',
                            'producto de matrices'
                        ],
                        'Transpuesta de una matriz' => [],
                        'Matrices. Propiedades' => [],
                        'Determinante de una matriz de orden 2x2 y 3x3' => [],
                        'Inversa de una matriz de orden 2x2 y 3x3' => []
                    ],
                    'SISTEMA DE ECUACIONES E INECUACIONES' => [
                        'Sistemas de ecuaciones lineales con dos y tres variables' => [],
                        'Métodos de solución (sustitución, reducción, igualación)' => [],
                        'Interpretación geométrica' => [],
                        'Sistemas de inecuaciones lineales' => []
                    ],
                    'RELACIONES BINARIAS Y FUNCIONES' => [
                        'Producto cartesiano' => [],
                        'Relación binaria' => []
                    ],
                    'FUNCIONES' => [
                        'Función, dominio y rango' => [],
                        'Gráficas de funciones' => [],
                        'Funciones elementales' => [
                            'constante',
                            'lineal',
                            'afin',
                            'cuadrática',
                            'valor absoluto',
                            'raíz cuadrada'
                        ],
                        'Dominio de funciones racionales e irracionales' => [],
                        'Operaciones con funciones' => [
                            'suma',
                            'resta',
                            'multiplicación',
                            'división'
                        ],
                        'Composición de funciones' => [],
                        'Tipos de funciones' => [
                            'inyectiva',
                            'suryectiva',
                            'biyectiva'
                        ],
                        'Función inversa' => [],
                        'Relación gráfica de una función y de su inversa' => [],
                        'Función exponencial. Propiedades' => []
                    ],
                    'FUNCIÓN EXPONENCIAL Y LOGARÍTMICA' => [
                        'Función exponencial' => [],
                        'Ecuaciones exponenciales' => [],
                        'Función logaritmica logaritmicas y propiedades' => [],
                        'Ecuaciones logarìtmicas' => []
                    ]
                ],
                'GEOMETRÍA' => [
                    'ÁNGULOS' => [
                        'Angulos. Definición' => [],
                        'Clasificación según su medida' => [],
                        'Clasificación de acuerdo a su posición y caracteristica' => [],
                        'Ángulos. Caracteristica' => []
                    ],
                    'TRIÁNGULO' => [
                        'Triángulo. Definición y clasificación' => [],
                        'Triángulo. Clasificación' => [],
                        'Teoremas fundamentales, congruencia de triángulos' => [],
                        'Congruencia de triángulos' => []
                    ],
                    'PROPORCIONALIDAD' => [
                        'Teorema de Thales' => [],
                        'Teorema de Thales aplicado a un triángulo' => []
                    ],
                    'RELACIONES MÉTRICAS EN UN TRIÁNGULO' => [
                        'Relaciones métricas en un triángulo rectángulo' => [],
                        'Teorema de Pitágoras' => [],
                        'Relaciones métricas en el triángulo oblicuángulo' => [],
                        'Teorema de proyecciones' => [],
                        'Teorema de la mediana' => [],
                        'Teorema de Herón' => []
                    ],
                    'POLÍGONOS' => [
                        'Polígoбos. Definición y clasificación' => [],
                        'Teoremas fundamentales' => [],
                        'Cuadriláteros' => []
                    ],
                    'LA RECTA' => [
                        'Pendiente de una recta' => [],
                        'Ángulo entre dos rectas' => [],
                        'Definición de la linea recta' => [],
                        'Ecuaciones de la recta conociendo un punto' => [],
                        'Ecuaciones de la recta conociendo la pendiente' => [],
                        'Ecuación de la recta que pasa por dos puntos' => [],
                        'Posiciones relativas a dos rectas' => [],
                        'Posiciones relativas rectas paralelas' => [],
                        'Posiciones relativas a rectas perpendiculares' => [],
                        'Distancia de un punto a una recta' => [],
                        'Distancia entre rectas paralelas' => [],
                        'Angulo entre dos rectas' => []
                    ],
                    'SECCIONES CÓNICAS' => [
                        'Ecuación cartesiana' => [
                            'Ordianria',
                            'General'
                        ],
                        'Elementos de la circunferencia' => [],
                        'Elementos de la parábola' => [],
                        'Elementos de la elipse' => [],
                        'Elementos de la hipérbola.' => []
                    ],
                    'GEOMETRÍA DEL ESPACIO' => [
                        'Recta y Plano' => [
                            'posiciones relativas entre rectas',
                            'planos en el espacio'
                        ],
                        'Teorema de Thales en el espacio' => [],
                        'Ángulos diedros' => [],
                        'Poliedros geométricos' => [],
                        'Teorema de Euler' => [],
                        'Poliedros regulares' => ['prisma y piramide'],
                        'Superficies de revolución' => [
                            'Cilindro',
                            'Cono',
                            'Esfera'
                        ]
                    ]
                ],
                'TRIGONOMETRÍA' => [
                    'ÁNGULO' => [
                        'sistemas de medida' => [],
                        'Fórmulas de conversión de unidades' => [],
                        'Razones trigonométricas en un triángulo rectángulo.' => [],
                        'Razones trigonométricas de ángulos notables de medidas 15°, 30°, 45°, 60° y 75°' => [],
                        'Resolución de triángulos rectángulos' => [],
                        'Ángulos de elevación y depresión' => [],
                        'Razones trigonométricas de otros ángulos.' => [],
                        'Identidades trigonométricas' => [
                            'recíprocas',
                            'por cociente',
                            'pitagóricas'
                        ],
                        'Identidades con arcos compuestos' => [
                            'razones trigonométricas de suma',
                            'razones trigonométricas de diferencia de arcos'
                        ],
                        'identidades del arco doble' => [],
                        'identidades del ángulo mitad' => [],
                        'Identidades para la suma y producto de senos y cosenos' => []
                    ],
                    '2.RESOLUCIÓN DE TRIÁNGULOS' => [
                        'Ley de senos' => [],
                        'ley de cosenos' => [],
                        'ley de tangentes' => [],
                        'Área de regiones triangulares' => [
                            'conociendo lados',
                            'conociendo altura',
                            'conociendo ángulos',
                            'conociendo semiperrímetro'
                        ],
                        'Circunferencia trigonométrica' => [],
                        'Cálculo de longitudes de las líneas notables de un triángulo' => [],
                        'Área de una región limitada por un polígono' => []
                    ],
                    '3.FUNCIONES TRIGONOMÉTRICAS' => [
                        'Funciones trigonométricas de números reales' => [],
                        'Dominio, rango y gráfica' => [],
                        'Funciones trigonométricas inversas y sus gráficas.' => [],
                        'Solución de ecuaciones trigonométricas.' => []
                    ]
                ]
            ],
            'CIENCIAS SOCIALES' => [
                'HISTORIA' => [
                    'LA HISTORIA COMO CIENCIA DEL HOMBRE EN EL TIEMPO' => [
                        'Conceptos de Historia' => [],
                        'Fuentes históricas' => [],
                        'Hechos y procesos históricos' => [],
                        'Categorías temporales' => []
                    ],
                    'ORIGEN DE LA HUMANIDAD A LAS CIVILIZACIONES DEL MUNDO CLÁSICO' => [
                        'El proceso de hominización' => [],
                        'La revolución neolítica' => [],
                        'Culturas antiguas de oriente, civilizaciones clásicas de occidente' => [
                            'Grecia',
                            'Roma'
                        ]
                    ],
                    'PRIMEROS POBLADORES A LOS ESTADOS REGIONALES EN LOS ANDES CENTRALES' => [
                        'Poblamiento de América' => [],
                        'Periodificación de las sociedades prehispánicas de los Andes Centrales' => [],
                        'Caral' => [],
                        'Chavin' => [],
                        'Paracas' => [],
                        'Nazca' => [],
                        'Moche' => [],
                        'Tiwanaku' => [],
                        'Wari' => [],
                        'Chimú' => [],
                        'Desarrollos culturales locales' => [
                            'Chuquibamba',
                            'Churajón'
                        ]
                    ],
                    'INVASIONES BÁRBARAS A LA EXPANSIÓN EUROPEA (S. XV-XVI)' => [
                        'Las invasiones bárbaras, el imperio bizantino' => [],
                        'La cultura Árabe' => [],
                        'El feudalismo' => [],
                        'El humanismo' => [],
                        'El renacimiento' => [],
                        'Los grandes descubrimientos geográficos' => [],
                        'Las expediciones portuguesas' => [],
                        'La reforma protestante' => [],
                        'La supremacia española del siglo XVI' => [],
                        'Las razones de la expansión europea' => []
                    ],
                    'ORÍGENES DEL TAHUANTINSUYO A INICIOS DEL VIRREINATO (S. XVI)' => [
                        'La formación del Tahuantinsuyo' => [],
                        'Origen y expansión Inca' => [],
                        'La economía de los Incas' => [],
                        'La organización de la sociedad andina' => [],
                        'La organización política y administrativa' => [],
                        'El arte y cultura Inca' => [],
                        'La cosmovisión y creencias religiosas' => [],
                        'Los españoles en el Tahuantinsuyo, la resistencia andina' => [
                            'los Incas de Vilcabamba',
                            'el enfrentamiento entre los conquistadores',
                            'las Leyes Nuevas',
                            'la creación del Virreinato',
                            'la organización del espacio',
                            'las consecuencias de la conquista'
                        ]
                    ],
                    'DESARROLLO DEL ABSOLUTISMO (S. XVII-XVIII) A LAS REVOLUCIONES LIBERALES (S. XIX)' => [
                        'El antiguo régimen' => [
                            'las monarquías absolutas',
                            'el poder hegemónico de Francia',
                            'conflictos entre monarquías',
                            'la decadencia española',
                            'el barroco',
                            'el siglo XVIII y la Ilustración',
                            'la burguesía en el siglo XVIII',
                            'la crisis del antiguo régimen',
                            'la revolución industrial',
                            'a independencia de los Estados Unidos de América',
                            'la revolución francesa, el neoclasicismo'
                        ]
                    ],
                    'ORGANIZACIÓN DEL VIRREINATO (S. XVII) AL SURGIMIENTO DE LA REPÚBLICA PERUANA' => [
                        'El Virreinato del Perú durante el siglo XVII' => [],
                        'Cambios en la economía del Virreinato' => [],
                        'La sociedad colonia' => [],
                        'La Iglesia y la evangelización' => [],
                        'Las reformas borbónicas y el Virreinato del siglo XVIII' => [],
                        'La influencia de los criollos en el Virreinato' => [],
                        'La rebelión indígena en el Perú, la formación-de nuestra identidad nacional' => [],
                        'La crisis política de España (1808-1812)' => [],
                        'Conspiraciones y rebeliones en el Virreinato en el Perú' => [],
                        'San Martin y la corriente libertadora del sur' => [],
                        'La proclamación de la independencia y protectorado' => [],
                        'El libertador Simón Bolivar' => [],
                        'La consolidación de la independencia' => [
                            'Junin',
                            'Ayacucho'
                        ]
                    ],
                    'LA SEGUNDA REVOLUCIÓN INDUSTRIAL A LA PRIMERA GUERRA MUNDIAL' => [
                        'La segunda revolución industrial (1870-1914)' => [],
                        'La expansión del capitalismo' => [],
                        'El movimiento obrero' => [],
                        'Imperialismo y colonialismo' => [],
                        'Los efectos del imperialismo en el mundo' => [],
                        'La paz armada (1890-1914)' => [],
                        'La Primera Guerra Mundial' => []
                    ],
                    'EL PRIMER MILITARISMO EN EL PERÚ A LA REPÚBLICA ARISTOCRÁTICA' => [
                        'EI primer militarismo y el nacimiento del Perú a la vida republicana' => [],
                        'la confederación Perú-boliviana' => [],
                        'la anarquía militar (1839-1845)' => [],
                        'la época del guano (1840-1866)' => [],
                        'la prosperidad falaz' => [],
                        'los gobiernos de la prosperidad' => [],
                        'el pensamiento político en el Perú hasta mediados del siglo XIX' => [],
                        'la Guerra con España' => [],
                        'la crisis nacional (1866- 1876)' => [],
                        'el gobierno del coronel José Balta' => [],
                        'el proyecto civilista' => [],
                        'factores los que desencadenaron la Guerra del Pacífico' => [],
                        'la Guerra del Pacífico' => [],
                        'la ocupación chilena' => [],
                        'el fin de la guerra y el Tratado de Ancón' => [],
                        'la reconstrucción del Perú y la república Aristocrática' => [],
                        'la predominancia del civilismo (1899-1908)' => [],
                        'el problema obrero en los inicios del siglo XX' => []
                    ],
                    'el problema obrero en los inicios del siglo XX' => [
                        'Crisis de las democracias en el período de Entreguerras (1919-1939)' => [],
                        'la Gran Depresión 1929' => [],
                        'Europa después dela Gran Depresión' => [],
                        'el fascismo en Italia' => [],
                        'el nazismo en Alemania' => [],
                        'las causas de la Segunda Guerra Mundial en Europa' => [],
                        'la expansión del conflicto' => [],
                        'el holocausto' => [],
                        'la organización de la paz' => [],
                        'consecuencias de la guerra' => [],
                        'la Guerra Fria (1947-1989)' => [],
                        'la URSS y el bloque oriental en la Guerra Fria' => [],
                        'los conflictos en Medio Oriente' => [],
                        'la configuración de la Unión Europea' => [],
                        'el ascenso de China la nueva potencia mundial' => [],
                        'la globalización' => [],
                        'nuevos movimientos sociales' => [],
                        'tecnología e inteligencia artificial' => []
                    ],
                    '1ONCENIO DE LEGUÍA A LA HISTORIA RECIENTE EN EL PERÚ (S. XXI)' => [
                        'El Oncenio: aspectos políticos y económicos' => [],
                        'El Oncenio' => [
                            'obras públicas y problemas fronterizos',
                            'el problema indígena',
                            'nuevos partidos políticos y agitación social',
                            'la crisis (1930-1933)',
                            'democracia entre dictaduras (1933-1945)'
                        ],
                        'gobierno de José Luis Bustamante y Rivero (1945-1948)' => [],
                        'populismo y crecimiento económico en el Perú' => [],
                        'el Gobierno Revolucionario de las Fuerzas Armadas (1968-1980)' => [],
                        'Crisis económica del Perú en los años ochenta' => [],
                        'movimientos subversivos' => [],
                        'el régimen de Alberto Fujimori' => [],
                        'la ruptura de las instituciones democráticas la Comisión de la Verdad y la Reconciliación' => [],
                        'el gobierno transitorio de Valentín Paniagua' => [],
                        'el gobierno de Alejandro Toledo' => [],
                        'el segundo gobierno de Alan García, la democracia en el Perú de inicios del siglo XXI' => [],
                        'la politica actual del Perú' => [],
                        'el gobierno de Ollanta Humala' => [],
                        'el gobierno de Pedro Pablo Kuczynski' => [],
                        'el gobierno de Martin Vizcarra' => [],
                        'caso Odebrecht y sus implicados' => [],
                        'los gobiernos transitorios de Manuel Merino y Francisco Sagasti' => [],
                        'el gobierno de Pedro Castillo y el actual gobierno de Dina Boluarte' => []
                    ]
                ],
                'GEOGRAFÍA' => [
                    'LA GEOGRAFÍA COMO CIENCIA DEL HOMBRE EN EL ESPACIO' => [
                        'Conceptos de Geografia' => [],
                        'Los grandes abordajes cientificos de la Geografia' => [],
                        'La Geografia y sus principios: localización' => [],
                        'distribución' => [],
                        'asociación' => [],
                        'interacción' => [],
                        'evolución y el principio de complejidad y de globalidad territorial' => [],
                        'Las entidades espaciales' => [],
                        'Las categorías espaciales' => []
                    ],
                    'EL ESPACIO GEOGRÁFICO DEL PERÚ' => [
                        'EI mar peruano' => [],
                        'La costa, la sierra y la selva' => []
                    ],
                    'LAS OCHO REGIONES NATURALES ALTITUDINALES DEL PERÚ' => [
                        'Chala' => [],
                        'Yunga maritima' => [],
                        'Quechua' => [],
                        'Suni' => [],
                        'Puna' => [],
                        'Janca o cordillera' => [],
                        'Yunga fluvial' => [],
                        'Rupa Rupa o selva alta' => [],
                        'Omagua o selva baja' => []
                    ],
                    'LAS ECORREGIONES DEL PERÚ' => [
                        'Mar frío' => [],
                        'mar tropical' => [],
                        'desierto del Pacífico' => [],
                        'bosque seco ecuatoria' => [],
                        'bosque tropical del Pacífico' => [],
                        'serranía esteparía' => [],
                        'páramo' => [],
                        'puna' => [],
                        'selva alta' => [],
                        'selva baja' => [],
                        'Sabana de palmeras' => []
                    ],
                    'LAS ÁREAS NATURALES PROTEGIDAS' => [
                        'Concepto de Áreas naturales protegidas por el Estado' => [],
                        'estatus de las Áreas naturales protegidas' => [],
                        'categorías de las Áreas naturales protegidas' => [
                            'Parques Nacionales',
                            'Reservas Nacionales',
                            'Santuarios Nacionales',
                            'Santuarios Históricos',
                            'Reservas paisajísticas',
                            'Refugios de vida silvestre',
                            'Reservas comunales',
                            'Bosques de protección',
                            'Cotos de caza',
                            'Zonas reservadas'
                        ]
                    ],
                    'LAS FRONTERAS DEL PERÚ Y LA INTEGRACIÓN DEMARCACIÓN Y ORGANIZACIÓN TERRITORIAL' => [
                        'Conceptos de bordes' => [],
                        'limites' => [],
                        'fronteras' => [],
                        'territorio' => [],
                        'territorialidad' => [],
                        'la linealidad' => [],
                        'la zonalidad' => [],
                        'procesos históricos de delimitación y organización territorial' => [],
                        'la demarcación territorial en el Perú' => [],
                        'las poblaciones de fronteras en el Perú' => [],
                        'las fronteras como espacios de integración' => [],
                        'frontera con Brasil' => [],
                        'delimitación fronteriza con Ecuador y Colombia' => [],
                        'delimitación fronteriza con Bolivia y Chile' => [],
                        'soberanía del Perú con el mar y su presencia en la Antártida' => []
                    ],
                    'ESPACIOS GEOGRÁFICOS DEL MUNDO' => [
                        'América' => [],
                        'Europa' => [],
                        'Asía' => [],
                        'Oceanía' => [],
                        'África' => [],
                        'Antártida' => [],
                        'Aspecto físico, características demográficas, desarrollo económico y calidad de vida' => []
                    ],
                    'INFORMACIÓN Y HERRAMIENTAS CARTOGRÁFICAS' => [
                        'ubicación' => [],
                        'coordenadas geográficas' => [],
                        'orientación' => [],
                        'distancia' => [],
                        'escalas cartográficas' => [],
                        'descripción' => [],
                        'representación' => [],
                        'digitalización del espacio geográfico' => [],
                        'tipos de mapas' => [],
                        'cartogramas' => [],
                        'coremas' => [],
                        'diatopos' => [],
                        'croquis' => [],
                        'esquemas gráficos' => [],
                        'imágenes' => [],
                        'tecnologías digitales' => []
                    ],
                    'PROBLEMÁTICAS AMBIENTALES' => [
                        'contaminación ambiental' => [],
                        'pérdida de la biodiversidad' => [],
                        'retroceso de los glaciares, explotación forestal' => [],
                        'erosión y desertificación de los suelos' => [],
                        'debilitamiento de la capa de ozono' => [],
                        'incidencia de exposición a los rayos ultravioleta' => [],
                        'incremento de enfermedades tropicales' => []
                    ],
                    'PROBLEMÁTICA TERRITORIAL' => [
                        'conflictos sociales' => [],
                        'fragmentación del territorio' => [],
                        'asentamientos humanos vulnerables' => [],
                        'condiciones de vida de la población' => []
                    ],
                    'POBLACIÓN Y CALIDAD DE VIDA EN EL PERÚ' => [
                        'Evolución histórica de la población peruana' => [],
                        'Estructura de la población' => [],
                        'el problema demográfico' => [],
                        'las migraciones internas en el Perú' => [],
                        'las migraciones externas' => [],
                        'las necesidades de la población' => [],
                        'crecimiento económico y desarrollo humano' => []
                    ],
                    'GESTIÓN INTEGRADA DE CUENCAS HIDROGRÁFICAS' => [
                        'Fundamentos teórico conceptuales de la cuenca hidrográfica' => [],
                        'la cuenca hidrográfica como unidad territorial multidimensional y multiescalar' => [],
                        'la ocupación del territorio en la cuenca' => [],
                        'Regiones hidrográficas del Perú' => [
                            'Región hidrográfica del Pacífico',
                            'Región hidrográfica del Amazonas',
                            'Región hidrográfica del Titicaca',
                            'Cuencas transfronterizas'
                        ]
                    ],
                    'EL ESPACIO GEOGRÁFICO DEL PERÚ Y LOS RECURSOS NATURALES' => [
                        'Factores de la oferta natural en el territorio peruano' => [],
                        'clasificación de los recursos naturales' => [],
                        'el desarrollo sostenible' => []
                    ],
                    'CAMBIO CLIMÁTICO' => [
                        'Adaptación y mitigación' => [],
                        'Estrategias ambientales globales, desarrollo sostenible' => [],
                        'La Convención Marco de las Naciones Unidas contra el cambio climático' => [],
                        'El Protocolo de Kioto' => [],
                        'La Alianza Mundial contra el cambio climático' => [],
                        'Acuerdo de Paris' => []
                    ],
                    'VULNERABILIDAD Y RIESGOS DE DESASTRES' => [
                        'en diferentes escalas y dimensiones económicas, políticas, sociales y culturales' => [],
                        'Fenómenos de geodinámica interna' => [
                            'sismos',
                            'actividad volcánica'
                        ],
                        'fenómenos producidos por geodinámica externa' => ['climatológicos'],
                        'peligros inducidos por la actividad humana' => [
                            'incendios',
                            'explosiones',
                            'derrames',
                            'contaminación'
                        ]
                    ]
                ]
            ],
            'CIENCIA Y TECNOLOGÍA' => [
                'QUIMICA' => [
                    'LA MATERIA' => [
                        'Definición. Materia' => [
                            'sustancias puras',
                            'elementos y compuestos',
                            'mezclas: homogénea y heterogéneas',
                            'separación de mezclas'
                        ],
                        'Propiedades fisicas y químicas de la materia' => [],
                        'Estados de la materia' => [],
                        'Cambios de la materia' => []
                    ],
                    'EL ÁTOMO' => [
                        'Estructura del atómico' => [
                            'núcleo',
                            'envoltura electrónica'
                        ],
                        'Número atómico' => [],
                        'Número de masa' => [],
                        'Isotopos' => [],
                        'Radioactividad' => [],
                        'Modelo atómico actual' => [],
                        'Números cuánticos' => [],
                        'Configuración electrónica de átomos e iones monoatómicos' => []
                    ],
                    'TABLA PERIÓDICA' => [
                        'Tabla periódica actual' => [],
                        'Grupos y Periodos' => [],
                        'Propiedades periódicas de los elementos' => [
                            'Metales',
                            'no metales',
                            'metaloides'
                        ],
                        'radio atómico' => [],
                        'radio iónico' => [],
                        'energia de ionización' => [],
                        'afinidad electrónica' => [],
                        'electronegatividad' => []
                    ],
                    'ENLACE QUÍMICO' => [
                        'Clases de enlaces' => [
                            'iónico',
                            'covalente',
                            'polar',
                            'no polar'
                        ],
                        'Estructura Lewis' => [],
                        'Enlace metálico' => [],
                        'propiedades de los metales' => [],
                        'Fuerzas intermoleculares' => []
                    ],
                    'COMPUESTOS INORGÁNICOS' => [
                        'Clasificación y nomenclatura de los compuestos químicos inorgánicos' => [],
                        'Función oxido' => ['Clasificación'],
                        'Función hidruro' => [],
                        'Función hidróxido' => [],
                        'Función ácida' => ['Clasificación'],
                        'Función sales' => ['Clasificación']
                    ],
                    'REACCIONES QUÍMICAS' => [
                        'Clases de reacciones' => [
                            'combinación',
                            'descomposición',
                            'desplazamiento simple y doble,',
                            'reacciones redox'
                        ],
                        'Reacciones en solución acuosa' => [
                            'ecuaciones',
                            'molecular',
                            'iónica total',
                            'neta'
                        ],
                        'Balanceo de ecuaciones redox en medio ácido' => [],
                        'medio básico' => []
                    ],
                    'ESTEQUIOMETRIA' => [
                        'El mol, peso, atómico, peso molecular, volumen molar, composición porcentual de compuestos químicos' => [],
                        'Relaciones estequiométricas de mol y masa con reactivos y productos' => [],
                        'Reactivo limitante' => [],
                        'Pureza de los reaccionantes' => [],
                        'Cálculos estequiométricos' => []
                    ],
                    'LA QUÍMICA ORGÁNICA' => [
                        'Propiedades del átomo de carbono' => [],
                        'Integración de la estructura molecular orgánica' => [
                            'Composición',
                            'constitución',
                            'configuración',
                            'conformación'
                        ]
                    ],
                    'HIDROCARBUROS' => [
                        'Clasificación' => [],
                        'Estructura y nomenclatura de los hidrocarburos alifáticos y aromáticos' => []
                    ],
                    'FUNCIONES OXIGENADAS' => [
                        'Estructura y nomenclatura' => [
                            'alcoholes',
                            'aldehidos',
                            'cetonas',
                            'ácidos carboxílicos',
                            'ésteres'
                        ]
                    ],
                    '1FUNCIONES NITROGENADAS' => [
                        'Estructura y nomenclatura de Aminas y amidas' => []
                    ]
                ],
                'BIOLOGIA' => [
                    'EL ORIGEN DE LA VIDA Y EVOLUCIÓN' => [
                        'Biología' => [
                            'Concepto',
                            'historia',
                            'ramas de la Biología'
                        ],
                        'Teorías del origen de la vida' => [],
                        'Niveles de organización de los seres vivos' => []
                    ],
                    'SERES VIVOS, DOMINIOS Y REINOS' => [
                        'Seres vivos' => [
                            'Características',
                            'clasificación de los seres vivos'
                        ],
                        'Categorías taxonómicas' => [
                            'Carlos Linneo',
                            'Robert Whittaker',
                            'Carl Woese'
                        ],
                        'Dominio Archaea' => ['Extremófilos'],
                        'Dominio Eubacteria' => [],
                        'Dominio Eukarya' => [
                            'Reino Protista',
                            'Reino Fungi',
                            'Reino Plantae',
                            'Reino Animalia'
                        ]
                    ],
                    'BIOQUÍMICA Y BIOLOGIA MOLECULAR' => [
                        'Bioquímica' => ['Biomoléculas orgánicas'],
                        'Glúcido' => ['Componentes y clasificación'],
                        'Lipidos' => ['Componentes y clasificación'],
                        'Proteínas y enzimas' => ['Componentes y clasificación'],
                        'Ácidos nucleicos' => ['Componentes y clasificación'],
                        'Dogma Central de la Biologìa.' => [],
                        'Genómica' => [],
                        'Proteómica' => [],
                        'Meta' => ['Aplicaciones actuales']
                    ],
                    'CÉLULA Y CICLO CELULA' => [
                        'Concepto de Célula, Tipos y estructura celular' => [],
                        'Membrana' => [],
                        'Citoplasma' => [],
                        'Núcleo' => [],
                        'Transporte a través de la membrana' => [],
                        'Ciclo celular' => ['Fases']
                    ],
                    'HISTOLOGÍA VEGETAL Y ANIMAL' => [
                        'Histología vegetal, clasificación' => ['Tejidos primarios y secundarios'],
                        'Histología animal, clasificación' => [
                            'Tejido epitelial',
                            'Tejido conectivo',
                            'Tejido muscular',
                            'Tejido nervioso'
                        ]
                    ],
                    'SISTEMA DIGESTIVO Y EXCRETOR' => [
                        'Sistema digestivo humano y animal' => ['estructura y función'],
                        'Glándulas anexas' => ['Tipos y funciones'],
                        'Sistema excretor humano y animal' => ['Clases de excreción'],
                        'Aparato urinario humano y anima' => [],
                        'Riñones' => ['Anatomía interna']
                    ],
                    'SISTEMA RESPIRATORIO Y CIRCULATORIO' => [
                        'Sistema respiratorio humano y animal' => ['estructura y función'],
                        'Sistema circulatorio humano y animal' => ['tipos y función'],
                        'Sistema cardiovascular' => [],
                        'Componentes (sangre, corazón, vasos) y funciones' => []
                    ],
                    'SISTEMA ENDOCRINO, NERVIOSO' => [
                        'Sistema endocrino humano y animal' => ['estructura y función de glándulas y hormonas'],
                        'Sistema nervioso humano y animal' => ['Tipos, estructura y función']
                    ],
                    'SISTEMA REPRODUCTOR' => [
                        'Sistema reproductor humano y animal' => [],
                        'Estructura, reproducción, Tipos' => [
                            'asexual',
                            'sexual'
                        ],
                        'ciclo reproductor femenino 0 ciclo menstrual' => [],
                        'Reproducción en plantas' => []
                    ],
                    'SISTEMA INMUNITARIO Y ENFERMEADES.' => [
                        'Sistema Inmune' => ['Definición, tipos, propiedades'],
                        'Órganos del sistema inmune' => ['Células linfoides y mieloides'],
                        'Respuesta inmune y líneas de defensa' => [],
                        'Inmunidad natural, inmunidad artificial' => [],
                        'Vacunas' => [],
                        'Enfermedad' => [],
                        'Agentes infecciosos, mecanismos de infección, fases.Tipos' => [
                            'genéticas',
                            'infecciosas',
                            'autoinmunes',
                            'degenerativas',
                            'metabólicas'
                        ]
                    ],
                    '1GENÉTICA Y BIOTECNOLOGÍA' => [
                        'Genética' => ['Generalidades.'],
                        'Cromosomas' => ['Estructura, tipos'],
                        'Leyes de la herencia de Mendel' => [],
                        'Tipos de herencia' => [],
                        'Determinación del sexo' => [],
                        'Herencia ligada al sexo' => [
                            'Daltonismo',
                            'hemofilia'
                        ],
                        'Enfermedades cromosómicas' => ['anomalías de número y estructura'],
                        'Epigenética' => [],
                        'Biotecnología animal y vegetal' => ['aplicaciones actuales']
                    ],
                    '1ECOLOGIA, CONSERVACION Y DESARROLLO SOSTENIBLE' => [
                        'Ecología' => [],
                        'Jerarquías, niveles y propiedades emergentes' => ['Organismo, Población, Comunidades'],
                        'Ecosistemas y Biomas' => ['Clases de biomas'],
                        'Biodiversidad' => ['Importancia'],
                        'Conservación ambiental' => ['Importancia, ordenamiento del espacio, conservación de los recursos naturales'],
                        'Areas Naturales Protegidas (ANP)' => [],
                        'Desarrollo sostenible' => ['Concepto y Objetivos de Desarrollo Sostenible (ODS)'],
                        'Deterioro ambiental' => [],
                        'cambio climático' => [],
                        'efecto invernadero' => [],
                        'adelgazamiento de la capa de ozono' => [],
                        'desertificación' => [],
                        'causas de la pérdida de la biodiversidad' => []
                    ]
                ],
                'FISICA' => [
                    'SISTEMAS DE MEDIDA' => [
                        'Unidades' => [],
                        'Conversión de unidades' => [],
                        'Dimensión de las magnitudes fisicas' => [],
                        'Notación cientifica' => []
                    ],
                    'MOVIMIENTO EN UNA DIMENSIÓN' => [
                        'Desplazamiento' => [],
                        'velocidad' => [],
                        'rapidez' => [],
                        'movimiento rectilineo uniforme' => [],
                        'movimiento rectilineo uniformemente variado' => [],
                        'objetos en caída libre' => [],
                        'diagramas de movimiento en MRU y MRUV' => []
                    ],
                    'MOVIMIENTO EN UN PLANO' => [
                        'El vector desplazamiento' => [],
                        'Suma y resta de vectores desplazamiento' => [],
                        'Producto de un vector por un escalar' => [],
                        'Componentes de los vectores' => [],
                        'Vectores unitarios' => [],
                        'Posición, velocidad y aceleración' => [],
                        'Movimiento de proyectil' => [],
                        'movimiento circular uniforme' => [],
                        'aceleración tangencial y radial' => [],
                        'velocidad y aceleración relativa' => []
                    ],
                    'LEYES DE NEWTON' => [
                        'Primera ley de Newton' => ['leyes de inercia'],
                        'Fuerza, masa' => [],
                        'segunda ley de Newton' => [],
                        'Fuerzas debido a la gravedad' => ['el peso'],
                        'Unidades de fuerza y masa' => [],
                        'Diagramas de fuerzas de sistemas aislados' => [],
                        'La tercera Ley de Newton' => [],
                        'Fuerzas de fricción' => ['rozamiento estático y cinético']
                    ],
                    'TRABAJO, ENERGÍA Y CONSERVACION DE ENERGIA MECÁNICA.' => [
                        'Movimiento en una dimensión con fuerzas constantes' => [],
                        'Teorema del trabajo-energía' => [],
                        'Producto escalar' => [],
                        'Potencia' => [],
                        'Conservación de la energía mecánica' => []
                    ],
                    'FLUIDOS' => [
                        'Densidad' => [],
                        'Presión en un fluido' => [],
                        'Flotación y principio de Arquimedes' => [],
                        'Fluidos en movimiento' => [
                            'ecuación de continuidad',
                            'ecuación de Bernoull'
                        ]
                    ],
                    'TEMPERATURA Y PROCESOS TERMICOS' => [
                        'Equilibrio térmico y temperatura' => [],
                        'Termómetros y sus escalas' => [],
                        'Celsius, Fahrenheit, Kelvin y Rankine' => [],
                        'Gas ideal' => [],
                        'Expansión térmica de sólidos' => [],
                        'Liquides fluidos.' => []
                    ],
                    'CALOR Y PRIMERA LEY DE LA TERMODINAMICA' => [
                        'Calor' => [],
                        'Calor específico y calorimetría' => [],
                        'Calor latente o cambios de fase' => [],
                        'Primera ley de la termodinámica' => [],
                        'La energía interna de un gas ideal' => []
                    ],
                    'ELECTROSTÁTICA' => [
                        'Propiedades de las cargas eléctricas' => [],
                        'Fuerza eléctrica' => [],
                        'La ley de Coulomb' => [],
                        'Campo eléctrico' => [],
                        'Líneas del campo eléctrico' => [],
                        'Flujo eléctrico' => [],
                        'Potencial eléctrico' => [],
                        'Capacitores' => ['capacitores en serie y paralelo']
                    ],
                    'ELECTRODINÁMICA' => [
                        'Corriente eléctrica, resistencia' => [],
                        'Ley de Ohm' => [],
                        'Circuitos' => [],
                        'Fuerza electromotriz' => [],
                        'Resistencia en serie y paralelo' => [],
                        'leyes de Kirchhoff' => []
                    ],
                    '1MAGNETISMO' => [
                        'Campo Magnético y Fuerza Magnética' => [],
                        'Movimiento de una carga en un campo magnético uniforme' => [],
                        'Inducción magnética' => [
                            'Flujo magnético',
                            'Ley de Faraday'
                        ]
                    ]
                ]
            ],
            'DESARROLLO PERSONAL, CIUDADANÍA Y CÍVICA' => [
                'PSICOLOGÍA' => [
                    'FUNDAMENTOS DE LA PSICOLOGÍA' => [
                        'Definición de psicología' => [],
                        'Objeto de la psicologia' => [],
                        'Fundamentos de la psicología como ciencia' => []
                    ],
                    'PROYECTO DE VIDA' => [
                        'Pautas para elaborar un proyecto de vida' => [],
                        'Análisis FODA' => [],
                        'Estrategias para maximizar fortalezas y abordar debilidades' => [],
                        'Proyecto de vida colectivo' => []
                    ],
                    'ORIENTACIÓN VOCACIONAL' => [
                        'Aspectos a considerar para realizar una buena orientación vocacional' => [],
                        'Uso del tiempo' => [],
                        'Estrategias' => [],
                        'La procrastinación' => []
                    ],
                    'HÁBITOS DE ESTUDIO' => [
                        'Las condiciones personales, ambientales, temporales (técnica POMODORO), técnicas' => []
                    ],
                    'BÚSQUEDA DE IDENTIDAD' => [
                        'Búsqueda de identidad, autoconocimiento, autoconcepto, autoestima y competencias sociales' => [],
                        'Identidad cultural' => [],
                        'Causas de la pérdida de identidad (globalización y migración)' => [],
                        'Identidad sexual' => [],
                        'Vida saludable' => []
                    ],
                    'MOTIVACIÓN Y AFECTIVIDAD HUMANA' => [
                        'Proceso motivacional, ciclo, motivacional, tipos de motivación' => [],
                        'Teorías de las necesidades humanas de Maslow' => [],
                        'Afectividad humana, la afectividad, características de la afectividad' => [],
                        'Las emociones, tipos de emociones, manejo de emociones, técnicas de autocontrol emocional (respiración, relajación, visualización, meditación, control del pensamiento o terapia cognitiva)' => [],
                        'Los sentimientos' => [],
                        'Diferencias entre emociones y sentimientos' => []
                    ],
                    'INTELIGENCIA EMOCIONAL' => [
                        'Inteligencia emocional, Componentes de la inteligencia emocional' => [
                            'bienestar subjetivo',
                            'felicidad',
                            'satisfacción con la vida',
                            'resiliencia',
                            'empatía',
                            'asertividad (manejo de conflictos)'
                        ]
                    ],
                    'FUNDAMENTOS DE LA PERSONALIDAD' => [
                        'El temperamento, definición, bases fisiológicas del temperamento, temperamento, tipos de temperamento' => ['carácter, definición, factores que influyen en su formación, tipos de carácter'],
                        'Diferencias entre temperamento y carácter' => [],
                        'Personalidad, definición, desarrollo de la personalidad, estructura de la personalidad' => []
                    ],
                    'APRENDIZAJE' => [
                        'Criterios del aprendizaje' => [],
                        'Teorías cognitivas del aprendizaje (teoría de Jean Piaget' => [],
                        'Teoría del aprendizaje significativo' => [],
                        'Teoría del aprendizaje por descubrimiento' => [],
                        'Teoria del aprendizaje por asociación' => [],
                        'Teoría del aprendizaje sociocultural)' => [],
                        'Estilos y estrategias de aprendizaje' => [],
                        'Técnicas para el análisis de contenidos' => [],
                        'Metacognición' => [],
                        'Aprendizaje autorregulado cooperativo' => [],
                        'Aprendizaje cooperativo' => [
                            'Elementos del aprendizaje cooperativo',
                            'procesamiento de la información',
                            'percepción',
                            'atención',
                            'memória y pensamienno creativo'
                        ],
                        'Percepción, caracteristicas de la percepción. Alteración de la percepción' => [],
                        'Atención, tipos de atención, trastornos de la atención' => [],
                        'Memoria, tipos de memoria, trastornos de la memoria' => [],
                        'Pensamiento, pensamiento creativo' => [],
                        'Inteligencia, inteligencias múltiples' => []
                    ],
                    'FACTORES DE PROTECCIÓN' => [
                        'Relación com los padres (tipos de familia, estilos de crianza)' => [],
                        'Interacción con el medio social (medios de comunicación, escuela, barrio, normas de convivencia),' => [],
                        'Relación con amigos' => []
                    ],
                    '1FACTORES DE RIESGO' => [
                        'violencia' => [],
                        'conductas delictivas y pandillaje' => [],
                        'consumo de sustancias psicoactivas' => [],
                        'desórdenes alimenticios' => [],
                        'conducta sexual de riesgo' => [],
                        'enfermedades de Transmisión Sexual (ETS)' => [],
                        'Deserción escolar' => [],
                        'suicidio' => []
                    ],
                    '1COMPORTAMIENTO SEXUAL SEXUALIDAD SALUDABLE Y Y REPRODUCTIVA' => [
                        'Etapas en la elección de pareja' => [],
                        'Comportamiento sexual' => [],
                        'Salud sexual' => [],
                        'Salud reproductiva' => [],
                        'Características del desarrollo óptimo de un adolescente sexualmente saludable' => [],
                        'Amistad' => [],
                        'Enamoramiento' => []
                    ],
                    '1CRECIMIENTO, MADURACIÓN Y DESARROLLO' => [
                        'Factores que determinan el desarrollo humano, dimensiones del desarrollo, psicología del desarrollo a través de la vida' => [],
                        'Desarrollo prenatal' => [
                            'etapa germinal',
                            'etapa embrionaria',
                            'etapa fetal'
                        ],
                        'Desarrollo posnatal' => [
                            'El proceso del nacimiento',
                            'Evaluación del recién nacido',
                            'Sistema para actuar los reflejos'
                        ],
                        'Etapa del nacimiento a los 2 años' => ['desarrollo fisico, motor, cognitivo, emocional y social'],
                        'Primera infancia (2 a 6 años)' => ['desarrollo fisico, motor, cognitivo, socioemocional'],
                        'Segunda infancia o niñez intermedia (6 a 12 años)' => ['desarrollo fisico, motor, cognitivo, socioemocional'],
                        'La adolescencia (de 12 a 20 años)' => ['desarrollo fisico, motor, cognitivo, socioemocional'],
                        'La juventud, adultez emergente o adultez temprana (de 20 a 40 años)' => ['desarrollo fisico, motor, cognitivo, socioemocional'],
                        'La madurez o adulteź media (de 40 años a 65 años)' => ['desarrollo fisico, motor, cognitivo, socioemocional.'],
                        'Etapa de vejez, senectud o adultez tardía (de 65 años a más)' => ['desarrollo fisico, motor, cognitivo, socioemocional.']
                    ]
                ],
                'FILOSOFÍA' => [
                    'LA FILOSOFÍA' => [
                        'El origen de la filosofia, circunstancias históricas' => [],
                        'Diferencias entre filosofia, ciencia y religión' => [],
                        'Caracteristicas del pensar filosófico' => ['Universalidad, profundidad, totalidad, criticidad, constructividad y vitalidad'],
                        'Los primeros filósofos' => ['La escuela de Mileto'],
                        'Los pitagóricos' => [],
                        'Heráclito y Parménides' => [],
                        'Los atomistas' => [],
                        'Los métodos filosóficos' => ['La mayéutica, la duda metódica, la fenomenología']
                    ],
                    'LÓGICA' => [
                        'La logica informal' => [],
                        '¿Qué son las falacias no formales? Tipos de falacia' => [],
                        'Falacias de relevancia' => ['Apelación a la emoción, la pista falsa, el hombre de paja, apelación a la fuerza, argumento contra la persona y conclusión irrelevante'],
                        'Falacias de inducción deficiente' => ['El argumento de la ignorancia, la apelación inapropiada a la autoridad, la causa falsa, Generalización precipitada'],
                        'Falacias de presuposición' => ['Accidente, pregunta compleja y petición de principio'],
                        'Falacias de ambigüedad' => ['Equivocación, anfibologia, acento, equivocación, y división']
                    ],
                    'GNOSEOLOGÍA Y EPISTEMOLOGÍA' => [
                        '¿Qué es el conocimiento? La posibilidad del conocimiento' => ['Dogmatismo, escepticismo, subjetivismo y relativismo'],
                        'El pragmatismo, el criticismo' => [],
                        'El origen del conocimiento' => [],
                        'El racionalismo, el empirismo, el intelectualismo, el apriorismo. La ciencia. Características del conocimiento científico' => [],
                        'El método científico' => [],
                        'Diversos tipos de ciencia' => [],
                        'Las ciencias formales y ciencias experimentales' => [],
                        'Los problemas del método experimental' => [],
                        'El problema de la inducción, el problema de la verificación' => [],
                        'El criterio de falsación' => []
                    ],
                    'FILOSOFÍA POLÍTICA' => [
                        'El poder social en las sociedades primitivas' => [],
                        'Origen del poder político' => [],
                        'Las legitimaciones del poder político' => ['La legitimidad tradicional, carismática y legal- racional'],
                        'Hobbes y el contrato social, Locke y el liberalismo' => [],
                        'El neocontractualismo' => ['La teoría de Rawls, la teoría de Nozick, la teoría de Habermas']
                    ],
                    'LA ÉTICA' => [
                        'Conceptos de ética y moral' => [],
                        'Diferencias entre ética y moral' => [],
                        'La reflexión ética' => [],
                        'El hombre como ser responsable' => [],
                        'La moralidad como búsqueda de felicidad' => ['La teoría ética de Aristóteles'],
                        'La moralidad como cumplimiento del deber' => ['La teoría ética kantiana'],
                        'Hechos y juicios morales' => [],
                        'La argumentación moral' => [],
                        'La teoría del desarrollo moral de Kohlberg' => [],
                        'eorias éticas de Peter Singer, de Martha Nussbaum' => [],
                        'Las éticas aplicadas' => [
                            'La bioética y sus cuatro principios clásicos'
                        ],
                        'la ética ambiental, y sus tres principios' => [
                            'El principio de justicia ambiental',
                            'el principio de respeto a la naturaleza',
                            'el principio de igualdad entre generaciones'
                        ],
                        'la ética empresarial' => []
                    ],
                    'DILEMAS MORALES' => [
                        'Qué son los dilemas morales? Tipos de dilemas morales, Dilemas reales e hipotéticos' => [
                            'dilemas abiertos o de solución',
                            'cerrados o de análisis'
                        ],
                        'Dilemas completos e incompletos' => []
                    ],
                    'ÉTICA Y CIUDADANÍA' => [
                        'Libertad y autonomía' => [],
                        '¿Qué es la alienación?' => [],
                        'Ser auténticos' => [],
                        'El ideal de buen ciudadano' => [],
                        'Principios y valores ciudadanos' => [],
                        'Convivencia ciudadana' => [],
                        'El bien público' => [],
                        'Construyendo la ciudadanía' => []
                    ],
                    'AXIOLOGÍA' => [
                        '¿Qué son los valores? Tipos de valores' => [],
                        'Los valores morales de responsabilidad, respeto, empatía, solidaridad' => [],
                        'Los valores sociales, la igualdad y la equidad, la libertad, la tolerancia, la justicia, la justicia restaurativa' => []
                    ]
                ],
                'CÍVICA' => [
                    'DERECHOS HUMANOS' => [
                        'Concepto, pautas básicas y generaciones de los DDHH' => [],
                        'Persona humana' => [],
                        'Derecho internacional' => [],
                        'Carta de las Naciones Unidas' => [],
                        'Organización de las Naciones Unidas' => [],
                        'Declaración Universal de los Derechos Humanos' => [],
                        'Pactos internacionales de derechos humanos' => [],
                        'Convención de Derechos humanos' => [],
                        'Organismos internacionales de protección de los Derechos humanos' => [],
                        'Rol del Estado y de la sociedad' => [],
                        'Constitución Política del Perú' => [],
                        'Medios de comunicación con derecho de acceso a la información.' => []
                    ],
                    'PARTICIPACIÓN CIUDADANA' => [
                        'Concepto, características, participación ciudadana' => [],
                        'Niveles de participación' => [],
                        'Mecanismos de participación ciudadana' => [],
                        'Mecanismos de control de ciudadano' => [],
                        'Otros mecanismos' => []
                    ],
                    'CONVIVENCIA' => [
                        'Problemas de convivencia en el Perú' => [],
                        'La delincuencia, el crimen organizado y la corrupción del manejo ambiental' => [],
                        'Valores cívicos (La honestidad. La justicia. Responsabilidad. Compromiso social. Solidaridad Social)' => [],
                        'Conservación y defensa del patrimonio natural' => [],
                        'Mecanismos democráticos de resolución de conflictos' => ['negociación, conciliación y mediación'],
                        'Feminicidio' => [],
                        'Violencia, crisis y reflexión en el Perú entre 1980-2000' => ['Terrorismo, Origen, Surgimiento, Responsabilidades, Derrota, Secuelas de violencia, Magnitud del conflicto, CVR, PIR, Terrorismo en la actualidad, Periodificación.']
                    ],
                    'SISTEMA DEMOCRÁTICO' => [
                        'Estructura del Estado peruano, poder legislativo, ejecutivo y judicial' => [],
                        'Órganos constitucionales autónomos' => ['Tribunal Constitucional (garantías constitucionales en el Perú)'],
                        'Ministerio Público' => [],
                        'Junta Nacional de Justicia' => [],
                        'Organismos del Sistema Financiero (Superintendencia de Banca, Seguros y Administradoras Privadas de Fondo de Pensiones. Banco Central de Reserva. SUNAT)' => [],
                        'Órgano de Defensa de la Nación, Defensoría del Pueblo' => [],
                        'Órgano de Control, Contraloría General de la Republica' => [],
                        'Sistema Electoral, Jurado Nacional de Elecciones' => [],
                        'Oficina Nacional de Procesos Electorales, Registro Nacional de Identificación y Estado Civil' => []
                    ],
                    'IDENTIDAD E INTERCULTURALIDAD' => [
                        'La Identidad nacional y su importancia (Los simbolos patrios, Héroes Nacionales, patriotismo, regionalismo y discurio chauvinista)' => [],
                        'Patrimonio Natural y Cultural' => [],
                        'Atentados contra el Patrimonio Natural y Cultural' => []
                    ],
                    'FUERZAS ARMADAS Y POLICIALES' => [
                        'Rol en la seguridad nacional y ciudadana' => [],
                        'Sistema Nacional de Gestión de Riesgos e Instituto de Defensa Civi' => [],
                        'CENEPRED' => [],
                        'Regimenes de excepción Construyendo nuestra identidad cultural (importancia, elementos y pérdida de identidad cultural)' => [],
                        'Diversidad lingüística' => [
                            'lenguas indigenas u originarias',
                            'problemática y avances en el ámbito nacional'
                        ]
                    ]
                ]
            ],
            'COMUNICACIÓN' => [
                'LENGUAJE' => [
                    'LENGUAJE Y COMUNICACIÓN' => [
                        'Lenguaje y definiciones. Caracteristicas del lenguaje humano' => [],
                        'Funciones del lenguaje' => [],
                        'Planos del lenguaje' => [],
                        'Variantes lingüísticas' => [],
                        'Lenguas y variedad lingüística en el Perú' => [],
                        'La comunicación' => []
                    ],
                    'PRAGAMATICA DE LA COMUNICACIÓN' => [
                        'Definición. Lingüística del texto' => [],
                        'El texto escrito y su proceso de composición' => [],
                        'La silaba' => []
                    ],
                    'ASPECTOS ORTOGRAFICOS' => [
                        'Concurrencia vocálica' => [],
                        'El acento y la tilde' => [],
                        'Las letras mayúsculas y minúsculas' => []
                    ],
                    'ORTOGRAFIA' => [
                        'Los signos de puntuación' => [],
                        'CATEGORIAS GRAMATICALES' => [],
                        'Palabras con escritura dudosa' => [],
                        'Los determinantes' => [],
                        'Los sustantivos' => []
                    ],
                    'PRONOMBRES Y ADJETIVOS' => [
                        'El pronombre' => [],
                        'El adjetivo' => []
                    ],
                    'VERBO Y ADVERBIO' => [
                        'El verbo, clasificaciones, criterios morfológicos y flexiones verbales' => [],
                        'El adverbio, definición, características y clases' => []
                    ],
                    'RELACIONANTES SINTAXIS' => [
                        'La preposición, definición y clases' => [],
                        'La conjunción, definición y clases' => [],
                        'a sintaxis, definición, clasificación de oraciones' => []
                    ],
                    'EL SUJETO Y EL PREDICADO' => [
                        'Definición y tipos' => [],
                        'Elementos y estructura del sujeto' => [],
                        'El predicado, definición y elementos' => [],
                        'Oración compuesta' => []
                    ],
                    'CITAS TEXTUALES' => [
                        'Definición y clases' => [],
                        'Uso de citas y estilos de citación APA 7' => []
                    ],
                    'TEXTOS ACADEMICOS Y TECNICAS DE PARTICIPACIÓN GRUPAL' => [
                        'Textos académicos' => ['monografia, ensayo argumentativo, tesis, articulo científico, guion, debate; seminario, coloquio, simposio, panel']
                    ],
                    '1OTROS TIPOS DE TEXTOS' => [
                        'Textos administrativos' => [],
                        'Lenguaje audiovisual' => [],
                        'Lenguaje periodístico' => [],
                        'Textos electrónicos científicos' => []
                    ]
                ],
                'LITERATURA' => [
                    'LITERATURA Y EXPRESIONES' => [
                        'La literatura oral' => [],
                        'Textos literarios y no literarios' => [],
                        'Formas de la expresión literaria' => ['La prosa y el verso']
                    ],
                    'LITERATURA UNIVERSAL' => [
                        'La Iliada / Homero' => [],
                        'Edipo Rey/Sófocles' => [],
                        'El poema de Mio Cid/Anónimo' => [],
                        'Lazarillo de Tormes / Anónimo' => [],
                        'El Quijote de la Mancha/Miguel de Cervantes' => [],
                        'Fuente Ovejuna/ Lope de Vega' => [],
                        'Werther/ Wolfang Goethe' => [],
                        'Rimas y leyendas / Gustavo Adolfo Bécquer' => [],
                        'Campos de Castilla / Antonio Machado' => [],
                        'Romancero gitano / Federico Garcia Lorca' => [],
                        'La metamorfosis / Franz Kafka' => [],
                        'El viejo y el mar/Ernest Hemingway' => [],
                        'Prosas Profanas / Rubén Darío' => [],
                        'Pedro Paramo / Juan Rulfo' => [],
                        'Los versos del capitán / Pablo Neruda' => [],
                        'Crónica de una muerte anunciada / Gabriel García Márquez' => [],
                        'El túnel/Ernesto Sábato' => [],
                        'Premios Nobel de los últimos años:' => ['Mo Yan, José Saramago, Gunter Grass, Imre Kertesz, Octavio Paz']
                    ],
                    'LITERATURA PERUANA' => [
                        'Mito de Inkarri' => [],
                        'Dioses y hombres de Huarochiri' => [],
                        'Crónicas' => [],
                        'Comentarios reales de los incas/ Inca Garcilaso de la Vega' => [],
                        'Nueva Crónica y Buen Gobierno / Guamán Poma de Ayala' => [],
                        'Ollantay/ Anónimo' => [],
                        'Tradiciones peruanas / Ricardo Palma' => [],
                        'Los hijos del sol / Abraham Valdelomar' => [],
                        'Simbólicas/ José María Eguren' => [],
                        'Los Heraldos Negros / César Vallejo' => [],
                        'El proceso de la literatura / José Carlos Mariátegui' => [],
                        'El mundo es ancho y ajeno/Ciro Alegría' => [],
                        'Yawar Fiesta / José María Arguedas' => [],
                        'El río/Javier Heraud' => [],
                        'La casa de cartón / Martin Adán' => [],
                        'Conversación en La Catedral/ Mario Vargas Llosa' => [],
                        'Crónica de San Gabriel / Julio Ramón Ribeyro' => [],
                        'Ese puerto existe / Blanca Varela' => []
                    ],
                    'LITERATURA REGIONAL' => [
                        'Ventura Travada' => [],
                        'Mariano Melgar' => [],
                        'Flora Tristán' => [],
                        'Maria Nieves y Bustamante' => [],
                        'Jorge Polar' => [],
                        'Gastón Aguirre Morales' => [],
                        'Cesar Atahualpa Rodríguez' => [],
                        'Alberto Guillen' => [],
                        'Percy Gibson' => [],
                        'Alberto Hidalgo' => [],
                        'Edmundo de los Rios Guillermo Mercado' => [],
                        'José Ruiz Rosas' => []
                    ]
                ]
            ],
            'IDIOMA EXTRANJERO' => [
                'INGLÉS-GRAMÁTICA' => [
                    'Expresiones sociales (Saludos y despedidas). Brinda información personal y de su entorno' => [],
                    'Expresa fechas, horas, cifras. Tiempo presente' => [],
                    'Vocabularios de familia, partes del cuerpo, vestimenta, nacionalidad, profesiones y deportes' => [],
                    'Vocabularios de alimentos, objetos, lugares, cantidades, colores y contenedores' => [],
                    'Números cardinales y ordinales' => [],
                    'Tiempo pasado' => [],
                    'Descripciones básicas (personas, objetos, lugares, etc.). Actividades de tiempo libre' => [],
                    'Tiempo futuro' => [],
                    'Artículos. Sustantivos' => [],
                    'Pronombres. Adjetivos' => [],
                    'Adverbios. Preposiciones' => [],
                    'Conectores. Preguntas' => [],
                    'Tipos de oraciones. Completar oraciones' => [],
                    'Sinonimia y antonimia. Encuentra términos excluidos' => [],
                    'Reordenar lecturas' => []
                ],
                'INGLÉS-LECTURA' => [
                ]
            ]
        ];

        // Función recursiva para crear bloques
        $this->createBlocks($estructura, null, '', $level1, $level2, $level3, $level4, $level5, $level6);
    }

    private function createBlocks($data, $parentBlock, $parentCode, $level1, $level2, $level3, $level4, $level5, $level6, $currentLevel = 1)
    {
        $counter = 1;
        
        foreach ($data as $key => $value) {
            // Determinar el nivel actual
            $levelModel = match($currentLevel) {
                1 => $level1,
                2 => $level2,
                3 => $level3,
                4 => $level4,
                5 => $level5,
                6 => $level6,
                default => $level6
            };

            // Generar código
            if ($currentLevel === 1) {
                $code = str_pad($counter, 2, '0', STR_PAD_LEFT);
            } else {
                $code = $parentCode . str_pad($counter, 2, '0', STR_PAD_LEFT);
            }

            // Crear el bloque
            $block = Block::create([
                'level_id' => $levelModel->id,
                'code' => $code,
                'name' => is_array($value) ? $key : $value,
                'parent_block_id' => $parentBlock?->id
            ]);

            // Si tiene hijos y no estamos en el nivel máximo, recursión
            if (is_array($value) && !empty($value) && $currentLevel < 6) {
                $this->createBlocks($value, $block, $code, $level1, $level2, $level3, $level4, $level5, $level6, $currentLevel + 1);
            }

            $counter++;
        }
    }
}